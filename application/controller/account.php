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
			elseif ( $action == 'buy product') {
				if ( $product = (int)$this->getPost('product_id')) {
					$dao = new dao\products;
					if ( $dto = $dao->getByID( $product)) {

						$total = $dto->rate;
						$tax = round( $total / 11, 2 );
						$rate = $total - $tax;

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
						    ->setTotal( $total)
						    ->setDetails( $details);

						$transaction = new PayPal\Api\Transaction;
						$transaction->setAmount( $amount)
						    ->setItemList( $itemList)
						    ->setDescription( "EasyDose License Purchase")
						    ->setInvoiceNumber( uniqid());


						/*--- ---[ final build of paypal payment object ]--- ---*/
						$payer = new PayPal\Api\Payer;
						$payer->setPaymentMethod("paypal");

						$redirectUrls = new PayPal\Api\RedirectUrls;
						$redirectUrls->setReturnUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=true'))
						    ->setCancelUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=false'));

						$_payment = new PayPal\Api\Payment;
						$_payment->setIntent("sale")
								    ->setPayer( $payer)
								    ->setRedirectUrls($redirectUrls)
								    ->setTransactions( [$transaction]);

						$payment = paypal::createPayment( $_payment);

						$a = [
							'payment_id' => $payment->id,
							'state' => $payment->state,
							'product_id' => $dto->id,
							'name' => $dto->name,
							'description' => $dto->description,
							'tax' => $tax,
							'value' => $total,
							'user_id' => currentUser::id(),
							'created' => \db::dbTimeStamp(),
							'updated' => \db::dbTimeStamp()
						];
						$dao = new dao\payments;
						$dao->Insert( $a);

						Response::redirect( $payment->getApprovalLink());

						// print ('<b>need to store the payment details here</b>');
						// \sys::dump( $a, NULL, FALSE);
						// \sys::dump( $dto, NULL, FALSE);
						// \sys::dump( $payment);

					}
					else { throw new \Exception('Invalid Product - cannot find product'); }

				}
				else { throw new \Exception('Invalid Product'); }

			}
			elseif ( $action == 'pay invoice') {
				if ( $id = (int)$this->getPost('id')) {
					$dao = new dao\invoices;
					if ( $inv = $dao->getByID( $id)) {
						if ( $inv->user_id == currentUser::id()) {
							$inv = $dao->getInvoice( $inv);



							/*--- ---[ pay invoice ]--- ---*/
							$items = [];
							$lineDescription = [];

							// ### Itemized information
							// (Optional) Lets you specify item wise
							// information
							foreach ( $inv->lines as $line) {
								$item = new PayPal\Api\Item;
								$item->setName( $line->description)
									->setCurrency('AUD')
									->setQuantity(1)
									->setSku( $line->name) // Similar to `item_number` in Classic API
									->setPrice( $line->rate - ($line->rate / \config::tax_rate_devisor));

								$items[] = $item;
								$lineDescription[] = $line->name;

							}

							$itemList = new PayPal\Api\ItemList;
							$itemList->setItems( $items);

							$details = new PayPal\Api\Details;
							$details
								->setTax( $inv->tax)
								->setSubtotal( $inv->total - $inv->tax);

							$amount = new PayPal\Api\Amount;
							$amount->setCurrency("AUD")
								->setTotal( $inv->total)
								->setDetails( $details);

							$transaction = new PayPal\Api\Transaction;
							$transaction->setAmount( $amount)
								->setItemList( $itemList)
								->setDescription( "EasyDose License Purchase")
								->setInvoiceNumber( $inv->id);

							/*--- ---[ final build of paypal payment object ]--- ---*/
							$payer = new PayPal\Api\Payer;
							$payer->setPaymentMethod("paypal");

							$redirectUrls = new PayPal\Api\RedirectUrls;
							$redirectUrls->setReturnUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=true'))
								->setCancelUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=false'));

							$_payment = new PayPal\Api\Payment;
							$_payment->setIntent("sale")
								->setPayer( $payer)
								->setRedirectUrls($redirectUrls)
								->setTransactions( [$transaction]);

							$payment = paypal::createPayment( $_payment);

							$a = [
								'payment_id' => $payment->id,
								'state' => $payment->state,
								'invoices_id' => $inv->id,
								'name' => 'invoice',
								'description' => implode(';',$lineDescription),
								'tax' => $inv->tax,
								'value' => $inv->total,
								'user_id' => currentUser::id(),
								'created' => \db::dbTimeStamp(),
								'updated' => \db::dbTimeStamp()
							];

							$dao = new dao\payments;
							$dao->Insert( $a);

							Response::redirect( $payment->getApprovalLink());

							// sys::dump( $inv);
							// print ('<b>need to store the payment details here</b>');
							/*--- ---[ pay invoice ]--- ---*/

						}
						else { throw new \Exceptions\InvoiceAccessViolation;}

					}
					else { throw new \Exceptions\InvoiceNotFound;}

				}
				else { throw new \Exceptions\InvoiceNotFound;}

			}
			elseif ( $action == 'create invoice') {
				// sys::dump( $this->getPost());
				if ( $product_id = (int)$this->getPost('product_id')) {
					$dao = new dao\products;
					if ( $dto = $dao->getByID( $product_id)) {

						$aInvoices = [
							'user_id' => currentUser::id(),
							'created' => \db::dbTimeStamp(),
							'updated' => \db::dbTimeStamp()
						];

						$aInvoicesDetail = [];
						$aInvoicesDetail[] = [
							'user_id' => currentUser::id(),
							'invoices_id' => 0,
							'product_id' => $dto->id,
							'rate' => $dto->rate,
							'created' => \db::dbTimeStamp(),
							'updated' => \db::dbTimeStamp()
						];

						if ( $workstation_id = (int)$this->getPost('workstation_id')) {
							if ( $dto = $dao->getByID( $workstation_id)) {
								$aInvoicesDetail[] = [
									'user_id' => currentUser::id(),
									'invoices_id' => 0,
									'product_id' => $dto->id,
									'rate' => $dto->rate,
									'created' => \db::dbTimeStamp(),
									'updated' => \db::dbTimeStamp()
								];

							}
							else { Response::redirect( url::tostring('account/createinvoice/'), 'Invalid workstation product'); }

						}

						if ( count($aInvoicesDetail)) {
							$dao = new dao\invoices;
							$invID = $dao->Insert( $aInvoices);

							$dao = new dao\invoices_detail;
							foreach ($aInvoicesDetail as $line) {
								$line['invoices_id'] = $invID;
								$dao->Insert( $line);

							}

							Response::redirect( url::tostring('account/invoice/' . $invID), 'created invoice');

						}
						else { Response::redirect( url::tostring('account/'), 'failed to create invoice'); }

					}
					else { Response::redirect( url::tostring('account/invoice/'), 'Invalid product'); }

				}
				else { Response::redirect( url::tostring('account/invoice/'), 'Invalid product'); }

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

		// sys::dump( $this->getParam('token'));

	}

	public function ExecutePayment() {
		// This is the second step required to complete
		// PayPal checkout. Once user completes the payment, paypal
		// redirects the browser to "redirectUrl" provided in the request.
		// This execute the payment that has been approved by
		// the buyer by logging into paypal site.

    // Get the payment Object by passing paymentId
    // payment id was previously stored in session in
    // CreatePaymentUsingPayPal.php
		$paymentId = $this->getParam('paymentId');
		$success = $this->getParam( 'success');
		if ( $success == 'true') {
			if ( $paymentId) {
				if ( $payment = paypal::executePayment( $paymentId, $this->getParam( 'PayerID'))) {
				// if ( $payment = paypal::payment( $paymentId)) {
					$dao = new dao\payments;
					if ( $dto = $dao->getByPaymentID( $payment->id)) {
						$a = [
							'state' => $payment->state,
							'cart' => $payment->cart,
							'updated' => \db::dbTimeStamp()

						];
						$dao->UpdateByID( $a, $dto->id);

						if ( $dto->invoices_id) {
							$dao = new dao\invoices;
							$dao->UpdateByID( $a, $dto->invoices_id);

						}

						Response::redirect( url::tostring('account'), sprintf( 'payment %s', $payment->state));
						// \sys::dump( $a, NULL, FALSE);
						// \sys::dump( $dto, NULL, FALSE);

					}
					else {
						throw new \Exception( 'could not retrieve payment to update');

					}

					// \sys::dump( $payment);

				}
				else {
					Response::redirect( url::tostring('account'), 'could not retrieve payment');

				}

			}
			else {
				throw new \Exception( 'invalid payment id');

			}

		}
		else {
			Response::redirect( url::tostring('account'), 'user cancelled payment');

		}

	}

	public function paypalSuccess() {
		$token = $this->getParam('token');
		// sys::dump( $this->getParam('token'));

	}

	public function paypalCancel() {
		$token = $this->getParam('token');
		// sys::dump( $this->getParam('token'));

	}

	protected function _index() {
		$debug = FALSE;
		// $debug = TRUE;

		$daoPlans = new dao\plans;
		$daoProducts = new dao\products;
		$daoAgreements = new dao\agreements;
		$daoInvoices = new dao\invoices;
		$daoGuid = new dao\guid;
		$this->data = (object)[
			'plans' => $daoPlans->getActivePlans(),
			'plansWKS' => $daoPlans->getActivePlans( $type = "WKS"),
			'products' => $daoProducts->getDtoSet(),
			'productsWKS' => $daoProducts->getDtoSet( $type = "WKS"),
			'guids' => $daoGuid->getForUser(),
			'invoices' => $daoInvoices->getForUser(),
			'agreementsForUser' => $daoAgreements->getAgreementsForUser( 0, $active = TRUE, $refresh = TRUE),
			'license' => FALSE
			];

		$daoLicense = new dao\license;
		$this->data->license = $daoLicense->getLicense();

		// sys::dump( $this->data);

		$p = new page( $this->title = 'My Account');
			$p
				->header()
				->title();

		if ( currentUser::isAdmin()) {
			$p->primary();
			$this->load('view');
			$p->secondary();
			$this->load('main-index');

		}
		else {
			$p->content();
			$this->load('view');
		}

	}

	public function invoice( $id = 0) {
		if ( $id = (int)$id) {
			$settings = new dao\settings;
			$dao = new dao\invoices;
			if ( $inv = $dao->getByID( $id)) {
				if ( currentUser::isAdmin() || $inv->user_id == currentUser::id()) {
					$this->data = (object)[
						'invoice' => $dao->getInvoice( $inv),
						'sys' => $settings->getFirst()
					];

				//	sys::dump( $this->data);

					$p = new page( $this->title = 'View Invoice');
						$p
							->header()
							->title();

						$p->primary();
							$this->load('view-invoice');

						$p->secondary();
							//~ $this->load('index');
							$this->load('main-index');


				}
				else { throw new \Exceptions\InvoiceAccessViolation;}

			}
			else { throw new \Exceptions\InvoiceNotFound;}

		}
		else { Response::redirect( url::tostring()); }

	}

	public function createinvoice() {
		$daoProducts = new dao\products;
		$settings = new dao\settings;
		$this->data = (object)[
			'products' => $daoProducts->getDtoSet(),
			'productsWKS' => $daoProducts->getDtoSet( $type = "WKS"),
			'sys' => $settings->getFirst()

		];

		$p = new page( $this->title = 'Create Invoice');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('create-invoice');

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
