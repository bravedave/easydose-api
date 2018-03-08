<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class settings extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');

		if ( $action == 'update') {
			$a = [
				'name' => $this->getPost( 'name'),
				'street' => $this->getPost('street'),
				'town' => $this->getPost('town'),
				'state' => $this->getPost('state'),
				'postcode' => $this->getPost('postcode'),
				'bank_bsb' => $this->getPost('bank_bsb'),
				'bank_account' => $this->getPost('bank_account'),
				'abn' => $this->getPost('abn')
			];

			if ( currentUser::isProgrammer()) {
				// $a['lockdown'] = (int)$this->getPost('lockdown');
				$a['paypal_ClientID'] = $this->getPost('paypal_ClientID');
				$a['paypal_ClientSecret'] = $this->getPost('paypal_ClientSecret');

			}

			$dao = new dao\settings;
			$dao->UpdateByID( $a, 1);

			Response::redirect( url::tostring('settings'), 'updated settings');

		}
		elseif ( $action == 'save plan') {
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
		elseif ( $action == 'delete plan') {
			if ( $id = $this->getPost('plan_id')) {
				$dao = new dao\plans;
				$dao->deleteByPayPalID( $id);
				Response::redirect( url::tostring('settings/plans'), paypal::deleteBillingPlan( $id));

			}

		}
		elseif ( $action == 'activate plan') {
			if ( $id = $this->getPost('plan_id')) {
				Response::redirect( url::tostring('settings/plans'), paypal::activateBillingPlan( $id));

			}

		}
		elseif ( $action == 'de-activate plan') {
			if ( $id = $this->getPost('plan_id')) {
				Response::redirect( url::tostring('settings/plans'), paypal::deactivateBillingPlan( $id));

			}

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
		$dao = new dao\settings;
		if ( $res = $dao->getAll()) {
			$this->data = $res->dto();

			//~ sys::dump( $this->data);

			$p = new page( $this->title = 'Settings');
				$p
					->header()
					->title();

				$p->primary();
					$this->load('settings');

				$p->secondary();
					$this->load('main-index');

		}
		else {
			throw new \Exception( 'missing system settings');

		}

	}

	public function index() {
		if ( $this->isPost())
			$this->postHandler();

		else
			$this->_index();

	}

	public function plans( $state = 'ACTIVE') {
		$state = strtoupper( $state);
		if ( $state == 'CREATED') {
			$this->data = (object)[
				'plans' => paypal::billingPlans( paypal::STATE_CREATED)
				];

			$p = new page( $this->title = 'Created Paypal Plans');

		}
		elseif ( $state == 'INACTIVE') {
			$this->data = (object)[
				'plans' => paypal::billingPlans( paypal::STATE_INACTIVE)
				];

			$p = new page( $this->title = 'Inactive Paypal Plans');

		}
		else {
			$this->data = (object)[
				'plans' => paypal::billingPlans( paypal::STATE_ACTIVE)
				];

			$p = new page( $this->title = 'Active Paypal Plans');

		}

			$p
				->header()
				->title();

			$p->primary();
				//~ sys::dump( $this->data);
				$this->load('plans');

			$p->secondary();
				$this->load('main-index');

	}

	public function plan( $id) {
		if ( 0 === strlen( (string)$id))
			throw new Exceptions\paypal;

		$this->data = (object)[
			'plan' => paypal::billingPlan( $id)
			];

		$p = new page( $this->title = 'Paypal Plan');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('plan-view');
				//~ sys::dump( $this->data, NULL, FALSE);

			$p->secondary();
				$this->load('main-index');

	}

	public function newplan() {
		//~ $this->data = (object)[
			//~ 'plans' => paypal::billingPlans()
			//~ ];

		$p = new page( $this->title = 'New Paypal Plan');
			$p
				->header()
				->title();

			$p->primary();
				//~ sys::dump( $this->data);
				$this->load('plan-edit');

			$p->secondary();
				$this->load('main-index');

	}

}
