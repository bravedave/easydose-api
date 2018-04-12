<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	security: admin only

	*/
class settings extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');

		if ( 'update' == $action) {
			$a = [
				'name' => (string)$this->getPost( 'name'),
				'street' => (string)$this->getPost('street'),
				'town' => (string)$this->getPost('town'),
				'state' => (string)$this->getPost('state'),
				'postcode' => (string)$this->getPost('postcode'),
				'bank_name' => (string)$this->getPost('bank_name'),
				'bank_bsb' => (string)$this->getPost('bank_bsb'),
				'bank_account' => (string)$this->getPost('bank_account'),
				'abn' => (string)$this->getPost('abn'),
				'invoice_email' => (string)$this->getPost('invoice_email')
			];

			if ( currentUser::isProgrammer()) {
				// $a['lockdown'] = (int)$this->getPost('lockdown');
				$a['paypal_live'] = (int)$this->getPost('paypal_live');
				$a['paypal_ClientID'] = (string)$this->getPost('paypal_ClientID');
				$a['paypal_ClientSecret'] = (string)$this->getPost('paypal_ClientSecret');

			}

			$dao = new dao\settings;
			$dao->UpdateByID( $a, 1);

			Response::redirect( url::tostring('settings'), 'updated settings');

		}
		elseif ( 'save plan' == $action) {
			$plan = new PayPal\Api\Plan;
			$plan
				->setName( $this->getPost('name'))
				->setDescription( $this->getPost('description'))
				->setType( $this->getPost('type'));

			$paymentDefinition = new PayPal\Api\PaymentDefinition;
				$paymentDefinition
					->setName('Regular Payments')
					->setType('REGULAR')
					->setFrequency( $this->getpost('frequency'))
					->setFrequencyInterval( $this->getpost('frequencyInterval'))
					->setCycles( $this->getpost('cycles'))
					->setAmount( new PayPal\Api\Currency( ['value' => $this->getpost('value'), 'currency' => 'AUD']));

			$merchantPreferences = new PayPal\Api\MerchantPreferences;
				/**
				 * ReturnURL and CancelURL are not required and used when creating billing agreement
				 * with payment_method as "credit_card".
				 * However, it is generally a good idea to set these values, in case you plan to create
				 * billing agreements which accepts "paypal" as payment_method. This will keep your plan
				 * compatible with both the possible scenarios on how it is being used in agreement.
				 */
				// http://localhost/account/ExecuteAgreement?success=false&token=EC-5XX12468YK8548539
				$merchantPreferences
					->setReturnUrl( url::$PROTOCOL . url::tostring( 'account/ExecuteAgreement/?success=true'))
					->setCancelUrl( url::$PROTOCOL . url::tostring( 'account/ExecuteAgreement/?success=false'));

				$merchantPreferences
					->setAutoBillAmount( 'yes')
					->setInitialFailAmountAction( 'CONTINUE')
					->setMaxFailAttempts( '0');
					//~ ->setSetupFee( new PayPal\Api\Currency(['value' => 1, 'currency' => 'USD']));

			$plan->setPaymentDefinitions( [$paymentDefinition]);	// done
			$plan->setMerchantPreferences($merchantPreferences);

			//~ sys::dump( $plan);
			Response::redirect( url::tostring('settings/plans/created'), paypal::createBillingPlan( $plan));

		}
		elseif ( 'delete plan' == $action) {
			if ( $id = $this->getPost('plan_id')) {
				$dao = new dao\plans;
				$dao->deleteByPayPalID( $id);
				Response::redirect( url::tostring('settings/plans'), paypal::deleteBillingPlan( $id));

			}

		}
		elseif ( 'activate plan' == $action) {
			if ( $id = $this->getPost('plan_id')) {
				Response::redirect( url::tostring('settings/plans'), paypal::activateBillingPlan( $id));

			}

		}
		elseif ( 'de-activate plan' == $action) {
			if ( $id = $this->getPost('plan_id')) {
				Response::redirect( url::tostring('settings/plans'), paypal::deactivateBillingPlan( $id));

			}

		}
		elseif ( 'reset database' == $action) {
			if ( currentUser::isProgrammer()) {
				if ( $confirmation = $this->getPost('confirmation')) {
					if ( 'Reset Confirmed' == $confirmation) {

						throw new \Exceptions\ResetDisabled;
						// $dbinfo = new dao\dbinfo;
					  // $dbinfo->reset();
						//
						// Response::redirect( 'logout');

					}
					else { throw new \Exceptions\MissingResetConfirmation; }

				}
				else { throw new \Exceptions\MissingResetConfirmation; }

			}
			else { throw new \Exceptions\AccessViolation; }

		}
		else {
			throw new dvc\Exceptions\InvalidPostAction( $action);

		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	protected function _index() {
		if ( currentUser::isAdmin()) {
			$dao = new dao\settings;
			if ( $res = $dao->getAll()) {
				$this->data = $res->dto();

				//~ sys::dump( $this->data);

				$this->render([
					'title' => $this->title = 'Settings',
					'primary' => 'settings',
					'secondary' => 'main-index']);

			}
			else { throw new \Exception( 'missing system settings'); }

		}

	}

	public function index() {
		if ( $this->isPost())
			$this->postHandler();

		else
			$this->_index();

	}

	public function plans( $state = 'ACTIVE') {
		if ( currentUser::isAdmin()) {
			$state = strtoupper( $state);
			if ( $state == 'CREATED') {
				$this->data = (object)[
					'plans' => paypal::billingPlans( paypal::STATE_CREATED)
				];

				$this->render([
					'title' => $this->title = 'Created Paypal Plans',
					'primary' => 'plans',
					'secondary' => 'main-index']);

			}
			elseif ( $state == 'INACTIVE') {
				$this->data = (object)[
					'plans' => paypal::billingPlans( paypal::STATE_INACTIVE)
				];

				$this->render([
					'title' => $this->title = 'Inactive Paypal Plans',
					'primary' => 'plans',
					'secondary' => 'main-index']);

			}
			else {
				$this->data = (object)[
					'plans' => paypal::billingPlans( paypal::STATE_ACTIVE)
				];

				$this->render([
					'title' => $this->title = 'Active Paypal Plans',
					'primary' => 'plans',
					'secondary' => 'main-index']);

			}

		}

	}

	public function plan( $id) {
		if ( currentUser::isAdmin()) {
			if ( 0 === strlen( (string)$id)) {
				throw new Exceptions\paypal;

			}

			$this->data = (object)[
				'plan' => paypal::billingPlan( $id)
			];

			$this->render([
				'title' => $this->title = 'Paypal Plan',
				'primary' => 'plan-view',
				'secondary' => 'main-index']);

		}

	}

	public function newplan() {
		if ( currentUser::isAdmin()) {
			$this->render([
				'title' => $this->title = 'New Paypal Plan',
				'primary' => 'plan-edit',
				'secondary' => 'main-index']);

		}

	}

}
