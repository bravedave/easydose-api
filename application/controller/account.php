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
			else {
				throw new \Exception( $action);

			}

		}
		else {
			throw new \Exception( 'invalid current user');

		}

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

				sys::dump( $a, NULL, FALSE);

			}
			sys::dump( $agreement);
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
		$daoPlans = new dao\plans;
		$daoAgreements = new dao\agreements;
		$daoGuid = new dao\guid;
		$this->data = (object)[
			'plans' => $daoPlans->getActivePlans(),
			'guids' => $daoGuid->getForUser(),
			'agreements' => $daoAgreements->getAgreementsForUser(),
			'agreement' => FALSE
			];


		$updated = FALSE;
		foreach ( $this->data->agreements as $dto) {
			if ( date( 'Y-m-d', strtotime( $dto->refreshed)) < date( 'Y-m-d')) {
				$daoAgreements->RefreshFromPayPal( $dto);
				$updated = TRUE;

			}

		}

		if ( $updated)
			$this->data->agreements = $daoAgreements->getAgreementsForUser();

		$this->data->agreement = $daoAgreements->getActiveAgreementForUser();

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
