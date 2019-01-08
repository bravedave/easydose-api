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
					'name' => (string)$this->getPost('name'),
					'email' => (string)$this->getPost('email'),
					'business_name' => (string)$this->getPost('business_name'),
					'street' => (string)$this->getPost('street'),
					'town' => (string)$this->getPost('town'),
					'state' => (string)$this->getPost('state'),
					'postcode' => (string)$this->getPost('postcode'),
					'abn' => (string)$this->getPost('abn')];

				// sys::dump( $_POST);

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
			// elseif ( $action == 'buy product') {
			// 	if ( $product = (int)$this->getPost('product_id')) {
			// 		$dao = new dao\products;
			// 		if ( $dto = $dao->getByID( $product)) {
			//
			// 			$total = $dto->rate;
			// 			$tax = round( $total / 11, 2 );
			// 			$rate = $total - $tax;
			//
			// 			// ### Itemized information
			// 			// (Optional) Lets you specify item wise
			// 			// information
			// 			$item1 = new PayPal\Api\Item;
			// 			$item1->setName( $dto->description)
			// 			    ->setCurrency('AUD')
			// 			    ->setQuantity(1)
			// 			    ->setSku( $dto->name) // Similar to `item_number` in Classic API
			// 			    ->setPrice( $rate);
			//
			// 			$itemList = new PayPal\Api\ItemList;
			// 			$itemList->setItems( [$item1]);
			//
			// 			$details = new PayPal\Api\Details;
			// 			$details
			// 			    ->setTax( $tax)
			// 			    ->setSubtotal( $rate);
			//
			// 			$amount = new PayPal\Api\Amount;
			// 			$amount->setCurrency("AUD")
			// 			    ->setTotal( $total)
			// 			    ->setDetails( $details);
			//
			// 			$transaction = new PayPal\Api\Transaction;
			// 			$transaction->setAmount( $amount)
			// 			    ->setItemList( $itemList)
			// 			    ->setDescription( "EasyDose License Purchase")
			// 			    ->setInvoiceNumber( uniqid());
			//
			//
			// 			/*--- ---[ final build of paypal payment object ]--- ---*/
			// 			$payer = new PayPal\Api\Payer;
			// 			$payer->setPaymentMethod("paypal");
			//
			// 			$redirectUrls = new PayPal\Api\RedirectUrls;
			// 			$redirectUrls->setReturnUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=true'))
			// 			    ->setCancelUrl( url::$PROTOCOL . url::tostring( 'account/ExecutePayment?success=false'));
			//
			// 			$_payment = new PayPal\Api\Payment;
			// 			$_payment->setIntent("sale")
			// 					    ->setPayer( $payer)
			// 					    ->setRedirectUrls($redirectUrls)
			// 					    ->setTransactions( [$transaction]);
			//
			// 			$payment = paypal::createPayment( $_payment);
			//
			// 			$a = [
			// 				'payment_id' => $payment->id,
			// 				'state' => $payment->state,
			// 				'product_id' => $dto->id,
			// 				'name' => $dto->name,
			// 				'description' => $dto->description,
			// 				'tax' => $tax,
			// 				'value' => $total,
			// 				'user_id' => currentUser::id(),
			// 				'created' => \db::dbTimeStamp(),
			// 				'updated' => \db::dbTimeStamp()
			// 			];
			// 			$dao = new dao\payments;
			// 			$dao->Insert( $a);
			//
			// 			Response::redirect( $payment->getApprovalLink());
			//
			// 			// print ('<b>need to store the payment details here</b>');
			// 			// \sys::dump( $a, NULL, FALSE);
			// 			// \sys::dump( $dto, NULL, FALSE);
			// 			// \sys::dump( $payment);
			//
			// 		}
			// 		else { throw new \Exception('Invalid Product - cannot find product'); }
			//
			// 	}
			// 	else { throw new \Exception('Invalid Product'); }
			//
			// }
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
								->setInvoiceNumber( sys::format_invoice_number( $inv->id));

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

							// sys::dump( $inv);

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
		// $daoProducts = new dao\products;
		$daoAgreements = new dao\agreements;
		$daoInvoices = new dao\invoices;
		$daoGuid = new dao\guid;
		$daoLicense = new dao\license;
	 	$users = new dao\users;

		// 'products' => $daoProducts->getDtoSet(),
		// 'productsWKS' => $daoProducts->getDtoSet( $type = "WKS"),

		$this->data = (object)[
			'account' => $users->getByID( currentUser::id()),
			'plans' => $daoPlans->getActivePlans(),
			'plansWKS' => $daoPlans->getActivePlans( $type = "WKS"),
			'guids' => $daoGuid->getForUser(),
			'invoices' => $daoInvoices->getForUser(),
			'agreementsForUser' => $daoAgreements->getAgreementsForUser( 0, $active = true, $refresh = true),
			'license' => false
			];

		$this->data->license = $daoLicense->getLicense();

		// sys::dump( $this->data->license);
		// sys::dump( [
		// 	$this->data->account,
		// 	$this->data->invoices]);

		if ( currentUser::isAdmin()) {
			$this->render([
				'title' => $this->title = 'My Account',
				'primary' => 'view',
				'secondary' => 'main-index'
			]);

		}
		else {
			$this->render([
				'title' => $this->title = 'My Account',
				'content' => 'view']);

		}

	}

	public function invoice( $id = 0) {
		if ( $id = (int)$id) {

			$send = (string)$this->getParam('send');
			$users = new dao\users;
			$settings = new dao\settings;
			$dao = new dao\invoices;
			$daoLicense = new dao\license;

			if ( $invoice = $dao->getByID( $id)) {
				if ( currentUser::isAdmin() || $invoice->user_id == currentUser::id()) {
					if ( $account = $users->getByID( $invoice->user_id)) {
						$this->data = (object)[
							'invoice' => $dao->getInvoice( $invoice),
							'account' => $account,
							'sys' => $settings->getFirst(),
							'license' => $daoLicense->getLicense( $invoice->user_id)

						];

						// sys::dump( $this->data);

						$p = $this->page(['title' => ( $this->title = 'View Invoice')]);
						$p
							->header()
							->title();

						$p->primary();

						$inv = new invoice(
							$this->data->sys,
						 	$this->data->account,
							$this->data->invoice,
						 	$this->data->license);

						$html = $inv->render();
						print $html;

						$this->load('invoice-options');

						$p->secondary();$this->load('main-index');

						if ( 'yes' == $send) {
							$mail = \sys::mailer();
							$mail->CharSet = 'UTF-8';
							$mail->Encoding = 'base64';

							if ( strings::IsEmailAddress( $this->data->sys->invoice_email)) {
								$mail->SetFrom( $this->data->sys->invoice_email, \config::$WEBNAME);

							}

							$mail->Subject  = \config::$WEBNAME . " Invoice";
							$mail->AddAddress( $this->data->account->email, $this->data->account->name );
							// $mail->AddAddress( 'david@brayworth.com.au', 'David Bray' );

							$mail->MsgHTML( $html);

							if ( $mail->send()) {
								print '<h2>Sent</h2>';

								$a = [
									'state' => 'sent',
									'state_change' => 'manual',
									'state_changed' => \db::dbTimeStamp(),
									'state_changed_by' => \currentUser::id(),
			            'updated' => \db::dbTimeStamp()

								];

								$dao = new dao\invoices;
								$dao->UpdateByID( $a, $invoice->id);

							}
							else {
								print '<h2>Error - NOT Sent</h2>';
								\sys::logger( 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);

							}

						}

					}
					else { throw new \Exceptions\InvoiceAccount;}

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
			'account' => currentUser::user(),
			'products' => $daoProducts->getDtoSet(),
			'productsWKS' => $daoProducts->getDtoSet( $type = "WKS"),
			'sys' => $settings->getFirst(),
			'personal' => '1',

		];

		$this->render([
			'title' => $this->title = 'Create Invoice',
			'primary' => 'invoice-create',
			'secondary' => 'main-index']);

	}

	public function index() {
		$this->isPost() ?
			$this->postHandler() :
			$this->_index();

	}

}
