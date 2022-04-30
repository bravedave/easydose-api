<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * description: data access worker for agreements table
*/

namespace dao;

use dvc\dao\_dao;

class agreements extends _dao {
	protected $_db_name = 'agreements';

	public function getAgreementsForUser( $userID = 0, $active = TRUE, $refresh = TRUE) {
		$debug = FALSE;
		// $debug = TRUE;

		if ( !(int)$userID) {
			$userID = \currentUser::id();

		}

		$_sql = 'SELECT
				a.id,
				a.agreement_id,
				a.plan_id,
				a.description,
				a.payment_method,
				a.name,
				a.start_date,
			 	strftime( "%Y-%m-%d", a.next_billing_date) `next_billing_date`,
				a.cycles_completed,
				a.frequency,
				a.value,
				a.refreshed,
				a.state,
				p.name `product`,
				p.description `productDescription`
			FROM agreements a
				LEFT JOIN plans p on a.plan_id = p.paypal_id';

		$_w = [
			'a.agreement_id != ""',
			sprintf( 'a.user_id = %d', $userID)
		];

		if ( $active) {
			$_w[] = 'a.state = "Active"';

		}

		$sql = sprintf( '%s WHERE %s', $_sql, implode( ' AND ', $_w));
		// \sys::logSQL( $sql);

		if ( $res = $this->Result( $sql)) {
			if ( $refresh) {
				while ( $dto = $res->dto()) {
					if ( date( 'Y-m-d', strtotime( $dto->refreshed)) < date( 'Y-m-d')) {
						if ( $debug) \sys::logger( sprintf('account/_index :: refreshFrom Paypal :: %s : %s', $dto->agreement_id, $dto->plan_id));
						$this->RefreshFromPayPal( $dto);

					}
					else {
						if ( $debug) \sys::logger( sprintf('account/_index :: Up to Date Paypal :: %s : %s', $dto->agreement_id, $dto->plan_id));

					}

				}

				if ( $res = $this->Result( $sql)) {
					return ( $res->dtoSet());

				}

			}
			else {
				return ( $res->dtoSet());

			}

		}

		return ( FALSE);

	}

	public function getActiveAgreementForUser( $userID = 0) {
		// $debug = TRUE;
		$debug = FALSE;

		$ret = new dto\license;

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
			strftime( "%Y-%m-%d", a.next_billing_date) `next_billing_date`,
			a.cycles_completed,
			a.frequency,
			a.value,
			a.refreshed,
			a.state,
			p.name `product`,
			p.description `productDescription`
				FROM agreements a
					LEFT JOIN plans p on a.plan_id = p.paypal_id';

		$_w = $_where;
		$_w[] = 'p.`name` NOT LIKE "WKS%"';
		$sql = sprintf( '%s WHERE %s', $_sql, implode( ' AND ', $_w));
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

		$_w = $_where;
		$_w[] = 'p.`name` LIKE "WKS%"';
		$sql = sprintf( '%s WHERE %s', $_sql, implode( ' AND ', $_w));
		if ( $res = $this->Result( $sql)) {
			if ( $ret->workstation = $res->dto()) {
				if ( 'WKSSTATION1' == $ret->workstation->product) {
					$ret->workstations = 2;

				}
				elseif ( 'WKSSTATION2' == $ret->workstation->product) {
					$ret->workstations = 3;

				}
				else {
					if ( $debug) \sys::logger( 'dao\agreements->getActiveAgreementForUser :: workstation license = ' . $ret->workstation->product );
					if ( $debug) \sys::dump( $ret->workstation);

				}

			}

		}
		else {
			if ( $debug) \sys::logger( 'dao\agreements->getActiveAgreementForUser :: no workstation license' );

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
