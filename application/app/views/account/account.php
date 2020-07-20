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

	// sys::dump( currentUser::user());
	// sys::dump( $this->data->account);

		?>
<form class="form" method="post" action="<?php url::write( 'account') ?>">
	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Username</div>
		<div class="col-10 col-sm-4">
			<input type="text" class="form-control" placeholder="user name" value="<?php print currentUser::username() ?>" readonly />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Name</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="name" class="form-control" placeholder="name"
				value="<?php print currentUser::name() ?>" autofocus />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Business Name</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="business_name" class="form-control" placeholder="business name"
			 	value="<?php print $this->data->account->business_name ?>" />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Address</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="street" class="form-control" placeholder="street"
				autocomplete="address-line1" value="<?php print $this->data->account->street ?>" />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-12 offset-sm-2 col-sm-8">
			<input type="text" name="town" class="form-control" placeholder="town"
				autocomplete="address-level2" value="<?php print $this->data->account->town ?>" />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-8 offset-sm-2 col-sm-5">
			<input type="text" name="state" class="form-control" placeholder="state"
			 	autocomplete="address-level3" value="<?php print $this->data->account->state ?>" />

		</div>

		<div class="col-4 col-sm-3">
			<input type="text" name="postcode" class="form-control" placeholder="postcode"
			 	autocomplete="postal-code" value="<?php print $this->data->account->postcode ?>" />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">ABN</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="abn" class="form-control"
				placeholder="ABN" value="<?php print $this->data->account->abn ?>" />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Email</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="email" class="form-control" placeholder="email" value="<?php print currentUser::email() ?>" <?php if ( !currentUser::isAdmin()) print 'readonly' ?> />

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-sm-2 col col-sm-10">
			<input type="submit" name="action" class="btn btn-outline-primary" value="update" />

		</div>

	</div>

</form>
