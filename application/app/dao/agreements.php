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

	public function getAgreementsForUser( $userID = 0, $active = TRUE) {
		if ( !(int)$userID)
			$userID = \currentUser::id();

		if ( $active) {
			/*

			 token,
			 result,
			 user_id,id,paypal_id,
			 state,frequency,rate

			 */

			$_sql = sprintf( 'SELECT
				a.id,
				a.agreement_id,
				a.plan_id,
				a.description,
				a.payment_method,
				a.name,
				a.start_date,
				a.next_billing_date,
				a.cycles_completed,
				a.frequency,
				a.value,
				a.refreshed,
				a.state,
				p.name `product`,
				p.description `productDescription`
				 	FROM agreements a
						LEFT JOIN plans p on a.plan_id = p.paypal_id
					WHERE a.agreement_id != "" AND a.state = "Active" AND a.user_id = %d', $userID);

		}
		else {
			$_sql = sprintf( 'SELECT * FROM agreements WHERE agreement_id != "" AND user_id = %d', $userID);

		}

		// \sys::logSQL( $_sql);

		if ( $res = $this->Result( $_sql))
			return ( $res->dtoSet());

		return ( FALSE);

	}

	public function getActiveAgreementForUser( $userID = 0) {
		$ret = (object)[
			'license' => FALSE,
			'workstation' => FALSE,
			'description' => '',
			'product' => '',
			'state' => '',
			'workstations' => 0,
			'expires' => '1970-01-01'

		];

		if ( !(int)$userID)
			$userID = \currentUser::id();

		$_where = [
			'a.agreement_id != ""',
			'a.state = "Active"',
			sprintf( 'a.user_id = %d', $userID)
		];

		$_sql =
			'SELECT
			a.id,
			a.agreement_id,
			a.plan_id,
			a.description,
			a.payment_method,
			a.name,
			a.start_date,
			a.next_billing_date,
			a.cycles_completed,
			a.frequency,
			a.value,
			a.refreshed,
			a.state,
			p.name `product`,
			p.description `productDescription`
				FROM agreements a
					LEFT JOIN plans p on a.plan_id = p.paypal_id';

		$sql = sprintf( '%s WHERE %s', $_sql, implode( ' AND ', $_where));
		// \sys::logSQL( $_sql);

		if ( $res = $this->Result( $sql)) {
			if ( $ret->license = $res->dto()) {
				$ret->product = $ret->license->product;
				$ret->description = $ret->license->description;
				$ret->workstations = 1;
				$ret->expires = $ret->license->next_billing_date;
				$ret->state = $ret->license->state;

			}

		}

		$_where[] = 'p.`name` LIKE "WKS%"';
		$sql = sprintf( '%s WHERE %s', $_sql, implode( ' AND ', $_where));
		if ( $res = $this->Result( $sql)) {
			if ( $ret->workstation = $res->dto()) {
				if ( 'WKSSTATION1' == $ret->workstation->name) {
					$ret->workstations = 2;

				}
				elseif ( 'WKSSTATION2' == $ret->workstation->name) {
					$ret->workstations = 3;

				}

			}

		}

		// \sys::dump( $ret);

		return ( $ret);

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
