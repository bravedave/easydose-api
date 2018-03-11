<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		viewer class for user table

	security:
	 	Ordinary Authenticated user - non admin

	*/

	$this->load('account');
 	$this->load('license');
 	$this->load('invoices');
 	$this->load('guids');
	if ( count( $this->data->agreementsForUser)) {
		/*
		*	Subscription Model
		* these are active aggreements which may include
		* EasyDose 5, 10 or OPEN and Workstation 1 or 2
		*/
		$this->load('agreementsForUser');

	}

	if ( $this->data->license->license) {
		/*
		* here there is an agreement,
		* but not necessarily a worksation agreement
		*/
		if ( !$this->data->license->workstation) {
			if ( !$this->data->license->type == 'SUBSCRIPTION') {
				$this->load('agreementWKS');	// offering opportunity to subscribe

			}
			else {
				$this->load('productsWKS');	// offering opportunity to purchase

			}

		}

		// \sys::dump($this->data->license);
		if ( $this->data->license->type == 'SUBSCRIPTION') {
			$this->load('activeAgreements');

		}
		else {
			$this->load('activeLicense');

		}

	}
	else {
		// there is no active agreement
		if ( sys::useSubscriptions()) {
			$this->load('plans');

		}

		$this->load('products-buy');

	}
