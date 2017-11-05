<?php
/**
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	reference:
		http://paypal.github.io/PayPal-PHP-SDK/

	description:
		This file contains the interactions with the paypal api
		
	*/
use PayPal\Api\Agreement;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;

abstract class paypal {
	const STATE_CREATED = 0;
	const STATE_ACTIVE = 1;
	const STATE_INACTIVE = 2;

	protected static function apiContext() {
		return ( new ApiContext( sys::paypalAuth()));

	}

	static function billingPlans( $state = self::STATE_ACTIVE) {
		try {
			/*
				Get the list of all plans You can modify different
				params to change the return list. The explanation about
				each pagination information could be found here at
				https://developer.paypal.com/webapps/developer/docs/api/#list-plans
			*/

			$params = ['page_size' => '10'];
			if ( $state == self::STATE_CREATED)
				$params['status'] = 'CREATED';

			elseif ( $state == self::STATE_ACTIVE)
				$params['status'] = 'ACTIVE';

			elseif ( $state == self::STATE_INACTIVE)
				$params['status'] = 'INACTIVE';

			$planList = Plan::all( $params, self::apiContext());

			if ( isset( $planList->plans)) {
				$dao = new dao\plans;
				foreach ( $planList->plans as $plan) {
					$a = [
						'name' => $plan->name,
						'description' => $plan->description,
						'state' => $plan->state];
						//~ ,
						//~ 'rate' => 0];

					if ( $dto = $dao->getByPayPalID( $plan->id)) {
						$dao->UpdateByID( $a, $dto->id);
						//~ \sys::logger( sprintf( 'found - updated : %s', $plan->id));

					}
					else {
						$a['paypal_id'] = $plan->id;
						$dao->Insert( $a);
						//~ \sys::logger( sprintf( 'NOT found - added %s', $plan->id));

					}

				}

			}
			//~ else {
				//~ \sys::dump( $planList);

			//~ }

			return $planList;

		}
		catch ( Exception $ex) {
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not list plans');

		}

	}

	static function billingPlan( $id) {
		try {
			$plan = Plan::get( $id, self::apiContext());
			if ( isset( $plan->id)) {
				$a = [
					'name' => $plan->name,
					'description' => $plan->description,
					'state' => $plan->state];

				$defs = $plan->payment_definitions;
				if ( count( $defs)) {
					$rate = 0;
					foreach ( $defs as $def) {
						$a['frequency'] = $def->frequency;
						$rate += (float)$def->amount->value;

					}

					$a['rate'] = $rate;

				}

				$dao = new dao\plans;
				if ( $dto = $dao->getByPayPalID( $plan->id)) {
					$dao->UpdateByID( $a, $dto->id);
					//~ \sys::logger( sprintf( 'found - updated : %s', $plan->id));

				}
				else {
					$a['paypal_id'] = $plan->id;
					$dao->Insert( $a);
					//~ \sys::logger( sprintf( 'NOT found - added %s', $plan->id));

				}

			}

			return ( $plan);

		}
		catch ( Exception $ex) {
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not retrieve plan : ' . $id);

		}

	}

	static function deleteBillingPlan( $id) {
		try {
			$apiContext =  self::apiContext();
			$plan = Plan::get( $id, $apiContext);
			$result = $plan->delete( $apiContext);
			return ( 'deleted plan');

		}
		catch ( Exception $ex) {
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not delete plan');

		}

	}

	static function activateBillingPlan( $id) {
		try {
			$apiContext =  self::apiContext();
			$plan = Plan::get( $id, $apiContext);

			$value = new PayPalModel('{"state":"ACTIVE"}');

			$patch = new Patch();
				$patch
					->setOp('replace')
					->setPath('/')
					->setValue( $value);

			$patchRequest = new PatchRequest();
				$patchRequest->addPatch( $patch);

			$plan->update( $patchRequest, $apiContext);

			return ( 'activated plan');

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not activate plan');

		}

	}

	static function deactivateBillingPlan( $id) {
		try {
			$apiContext =  self::apiContext();
			$plan = Plan::get( $id, $apiContext);

			$value = new PayPalModel('{"state":"INACTIVE"}');

			$patch = new Patch();
				$patch
					->setOp('replace')
					->setPath('/')
					->setValue( $value);

			$patchRequest = new PatchRequest();
				$patchRequest->addPatch( $patch);

			$plan->update( $patchRequest, $apiContext);

			return ( 'de-activated plan');

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not de-activate plan');

		}

	}

	static function createBillingPlan( Plan $plan) {
		try {
			$output = $plan->create( self::apiContext());
			return ( 'created plan');

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not create plan');

		}

	}

	static function agreement( $id) {
		try {
			return Agreement::get( $id, self::apiContext());

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not create aggreement');

		}

	}

	static function createAgreement( Agreement $agreement) {
		try {
			$output = $agreement->create( self::apiContext());
			return ( $output);

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not create aggreement');

		}

	}

	static function executeAgreement( $token) {
		$agreement = new \PayPal\Api\Agreement;
		try {
			/**
			 * Execute the agreement by passing in the token
			 */
			$apiContext =  self::apiContext();
			$agreement->execute( $token, $apiContext);

			return ( $agreement);

		}
		catch ( Exception $ex) {
			//~ sys::dump( $ex);
			sys::logger( $ex->getMessage());
			throw new \Exceptions\Paypal( 'could not execute aggreement');

		}

	}

}
