<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		data access worker for agreements table

	*/

Namespace dao;

class agreements extends _dao {
	protected $_db_name = 'agreements';

	public function getAgreementsForUser( $userID = 0) {
		if ( !(int)$userID)
			$userID = \currentUser::id();

		$_sql = sprintf( 'SELECT * FROM agreements WHERE agreement_id != "" AND user_id = %d', $userID);
		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	public function getActiveAgreementForUser( $userID = 0) {
		if ( !(int)$userID)
			$userID = \currentUser::id();

		$_sql = sprintf( 'SELECT * FROM agreements WHERE agreement_id != "" AND state = "Active" AND user_id = %d', $userID);
		if ( $res = $this->Result( $_sql))
			return ( $res->dto());

		return ( FALSE);

	}

	public function getAgreementByToken( $token) {
		$_sql = sprintf( "SELECT * FROM agreements WHERE `token` = '%s'", $token);
		if ( $res = $this->Result( $_sql))
			return ( $res->dto());

		return ( FALSE);

	}

	public function getAgreementByAgreementID( $agreementId) {
		$_sql = sprintf( "SELECT * FROM agreements WHERE `agreement_id` = '%s'", $agreementId);
		if ( $res = $this->Result( $_sql))
			return ( $res->dto());

		return ( FALSE);

	}

	public function RefreshFromPayPal( dto\dto $dto) {
		// \sys::dump( $dto);
		if ( $ag = \paypal::agreement( $dto->agreement_id)) {
			$a = [
				'name' => sprintf( '%s %s', $ag->payer->payer_info->first_name, $ag->payer->payer_info->last_name),
				'description' => $ag->description,
				'payment_method' => $ag->payer->payment_method,
				'start_date' => $ag->start_date,
				'next_billing_date' => $ag->agreement_details->next_billing_date,
				'frequency' => $ag->plan->payment_definitions[0]->frequency,
				'cycles_completed' => $ag->agreement_details->cycles_completed,
				'value' => $ag->plan->payment_definitions[0]->amount->value,
				'state' => $ag->state,
				'refreshed' => \db::dbTimeStamp()
			];

			// \sys::dump( $a, NULL, FALSE);
			// \sys::dump( $agreement);

			$this->UpdateByID( $a, $dto->id);

		}

	}

}
