<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for accessing the user account

	security:
	 	Ordinary Authenticated user - non admin

	*/
class account extends Controller {
	public function postHandler() {
		if ( currentUser::id()) {
			$action = $this->getPost('action');
			if ( $action == 'update') {
				$a = [
					'name' => $this->getPost('name'),
					'email' => $this->getPost('email')];

				$dao = new dao\users;
				$dao->UpdateByID( $a, currentUser::id());

				Response::redirect( url::tostring('account'), 'updated');

			}
			elseif ( $action == 'update-agreement-assignment') {
				if ( $id = (int)$this->getPost('id')) {
					if ( $agreements_id = (int)$this->getPost('agreements_id')) {
						$dao = new dao\guid;
						$dao->UpdateByID([
							'agreements_id' => $agreements_id,
							'updated' => \db::dbTimeStamp()
						], $id);

						Response::Redirect( url::tostring('account'), 'assigned agreement');

					}
					else {
						throw new \Exception( $action . ': invalid plan id');

					}

				}
				else {
					throw new \Exception( $action . ': invalid guid id');

				}

			}
			elseif ( $action == 'subscribe') {
				$agreement = new PayPal\Api\Agreement;

				$agreement->setName('EasyDose Subscription')
					->setDescription('EasyDose Subscription')
					->setStartDate( date( 'Y-m-d', strtotime( 'tomorrow' )) . 'T10:00:00Z');
					//~ ->setStartDate( date( 'c'));
					//~ ->setStartDate( '2019-06-17T9:45:04Z');

				// Add Plan ID Please note that the plan Id should be only set in this case.
				$plan = new PayPal\Api\Plan;
				$plan->setId( $this->getPost('plan_id'));
				$agreement->setPlan( $plan);

				// Add Payer
				$payer = new PayPal\Api\Payer;
				$payer->setPaymentMethod('paypal');
				$agreement->setPayer( $payer);

				//~ sys::dump( $agreement, NULL, FALSE);
				//~ sys::dump( $_POST);

				/*
				 * Note: the agreement has not yet activated,
				 * we wont be receiving the ID just yet.
				 */

				$agreement = paypal::createAgreement( $agreement);
				// Get redirect url
				// The API response provides the url that you must redirect the buyer to.
				// Retrieve the url from the $agreement->getApprovalLink() method

				$approvalUrl = $agreement->getApprovalLink();
				$_ = explode( '=', $approvalUrl);
				$token = array_pop( $_);
				$dao = new dao\agreements;
				$dao->Insert([
					'token' => $token,
					'plan_id' => $agreement->plan->id,
					'user_id' => currentUser::id()]);

				//~ die( $approvalUrl);

				//~ sys::dump( $agreement, NULL, FALSE);
				//~ sys::dump( $_POST);

				Response::redirect( $approvalUrl);

				return $agreement;

			}
			elseif ( $action == 'unsubscribe') {
				$agreementId = $this->getPost( 'agreement_id');
				$agreement = new PayPal\Api\Agreement;

        $agreement->setId( $agreementId);

        try {
					$j = \Json::ack( $action);
					$dao = new dao\agreements;
					if ( $dto = $dao->getAgreementByAgreementID( $agreementId)) {
						$agreement = paypal::cancelAgreement( $agreement);

						$dao->UpdateByID(['refreshed' => ''], $dto->id);	// refresh is required
						$j->add( 'agreement_id', $dto->agreement_id);
						$j->add( 'state', 'cancelled');

					}

        }
				catch (Exception $ex) {
					\Json::nak( $action);

        }


			}
			elseif( $action == 'buy product') {
				if ( $product = (int)$this->getPost('product_id')) {
					$dao = new dao\products;
					if ( $dto = $dao->getByID( $product)) {

						$tax = round( $dto->rate / 11, 2 );
						$rate = $dto->rate - $tax;

						$payer = new PayPal\Api\Payer;
						$payer->setPaymentMethod("paypal");
						// ### Itemized information
						// (Optional) Lets you specify item wise
						// information
						$item1 = new PayPal\Api\Item;
						$item1->setName( $dto->description)
						    ->setCurrency('AUD')
						    ->setQuantity(1)
						    ->setSku( $dto->name) // Similar to `item_number` in Classic API
						    ->setPrice( $rate);

						$itemList = new PayPal\Api\ItemList;
						$itemList->setItems( [$item1]);

						$details = new PayPal\Api\Details;
						$details
						    ->setTax( $tax)
						    ->setSubtotal( $rate);

						$amount = new PayPal\Api\Amount;
						$amount->setCurrency("AUD")
						    ->setTotal( $dto->rate)
						    ->setDetails( $details);

						$transaction = new PayPal\Api\Transaction;
						$transaction->setAmount( $dto->rate)
						    ->setItemList( $itemList)
						    ->setDescription( "EasyDose License Purchase")
						    ->setInvoiceNumber( uniqid());

						$redirectUrls = new PayPal\Api\RedirectUrls;
						$redirectUrls->setReturnUrl( url::$PROTOCOL . url::tostring( 'account/paypalSuccess'))
						    ->setCancelUrl( url::$PROTOCOL . url::tostring( 'account/paypalCancel'));
						// $redirectUrls->setReturnUrl( 'https://my.easydose.net.au/account/paypalSuccess')
						//     ->setCancelUrl( 'https://my.easydose.net.au/account/paypalCancel');

						$payment = new PayPal\Api\Payment;
						$payment->setIntent("sale")
								    ->setPayer( $payer)
								    ->setRedirectUrls($redirectUrls)
								    ->setTransactions( [$transaction]);

						$output = paypal::createPayment( $payment);

						\sys::dump( $dto, NULL, FALSE);
						\sys::dump( $output);

					}
					else { throw new \Exception('Invalid Product - cannot find product'); }

				}
				else { throw new \Exception('Invalid Product'); }

			}
			else { new \Exception( $action); }

		}
		else { throw new \Exceptions\InvalidUser;	}

	}

	public function ExecuteAgreement() {
		$token = $this->getParam('token');
		$success = $this->getParam( 'success');
		$dao = new dao\agreements;
		if ( $dto = $dao->getAgreementByToken( $token )) {
			$aggreementID = $dto->id;
			$dao->UpdateByID(['result' => $success], $dto->id);

		}
		else {
			$aggreementID = $dao->Insert([
				'token' => $token,
				'result' => $success,
				'user_id' => currentUser::id()
			]);

		}

		if ( $success == 'true') {
			/*-- [execute agreement] --*/
			if ( $agreement = paypal::executeAgreement( $token)) {
				// an error will be thrown otherwise
				$a = ['agreement_id' => $agreement->getId()];
				$dao->UpdateByID( $a, $dto->id);

				// sys::dump( $a, NULL, FALSE);

			}
			// sys::dump( $agreement);
			/*-- [end: execute agreement] --*/

			Response::redirect( url::tostring('account'), $success);

		}
		else {
			Response::redirect( url::tostring('account'), 'user cancelled approval');

		}

		sys::dump( $this->getParam('token'));

	}

	public function paypalSuccess() {
		$token = $this->getParam('token');
		sys::dump( $this->getParam('token'));

	}

	public function paypalCancel() {
		$token = $this->getParam('token');
		sys::dump( $this->getParam('token'));

	}

	protected function _index() {
		$debug = FALSE;
		// $debug = TRUE;

		$daoPlans = new dao\plans;
		$daoProducts = new dao\products;
		$daoAgreements = new dao\agreements;
		$daoGuid = new dao\guid;
		$this->data = (object)[
			'plans' => $daoPlans->getActivePlans(),
			'plansWKS' => $daoPlans->getActivePlans( $type = "WKS"),
			'products' => $daoProducts->getDtoSet(),
			'guids' => $daoGuid->getForUser(),
			'agreementsForUser' => $daoAgreements->getAgreementsForUser( 0, $active = FALSE),
			'agreement' => FALSE,
			'agreementWKS' => FALSE
			];


		foreach ( $this->data->agreementsForUser as $dto) {
			if ( date( 'Y-m-d', strtotime( $dto->refreshed)) < date( 'Y-m-d')) {
				if ( $debug) \sys::logger( sprintf('account/_index :: refreshFrom Paypal :: %s : %s', $dto->agreement_id, $dto->plan_id));
				$daoAgreements->RefreshFromPayPal( $dto);

			}
			else {
				if ( $debug) \sys::logger( sprintf('account/_index :: Up to Date Paypal :: %s : %s', $dto->agreement_id, $dto->plan_id));

			}

		}

		$this->data->agreementsForUser = $daoAgreements->getAgreementsForUser();

		$this->data->agreement = $daoAgreements->getActiveAgreementForUser();
		$this->data->agreementWKS = $daoAgreements->getActiveAgreementForUser(0, $type = 'WKS');

		// sys::dump( $this->data);

		$p = new page( $this->title = 'My Account');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('view');

			$p->secondary();
				//~ $this->load('index');
				$this->load('main-index');

	}

	public function index() {
		if ( $this->isPost())
			$this->postHandler();

		else
			$this->_index();

	}

}
