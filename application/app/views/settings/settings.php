<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<form class="form" method="POST" action="<?php url::write( 'settings') ?>" >
<?php	if ( $this->data) {	?>
	<div class="form-group row py-1">
		<label class="col-3" for="name">Name</label>
		<div class="col-9">
			<input type="text" name="name" class="form-control" autofocus
				value="<?php print $this->data->name ?>" />

		</div>

	</div>

	<div class="form-group row pt-1">
		<label class="col-3" for="name">Address</label>
		<div class="col-9">
			<input type="text" name="street" class="form-control" placeholder="PO Box / street address" autocomplete="address-line1"
				value="<?php print $this->data->street ?>" />

		</div>

	</div>

	<div class="form-group row py-0">
		<div class="offset-3 col-5">
			<input type="text" name="town" class="form-control" placeholder="town / suburb" autocomplete="address-level2"
				value="<?php print $this->data->town ?>" />

		</div>

	</div>

	<div class="form-group row pb-1">
		<div class="offset-3 col-3">
			<input type="text" name="state" class="form-control" placeholder="state" autocomplete="address-level3"
				value="<?php print $this->data->state ?>" />

		</div>

		<div class="col-2">
			<input type="text" name="postcode" class="form-control" placeholder="postcode" autocomplete="postal-code"
				value="<?php print $this->data->postcode ?>" />

		</div>

	</div>

	<div class="form-group row py-1">
		<label class="col-3" for="ABN">ABN</label>
		<div class="col-3">
			<input type="text" name="abn" id="ABN" class="form-control" placeholder="00 000 000 000"
				value="<?php print $this->data->abn ?>" />

		</div>

	</div>

	<div class="form-group row py-1">
		<label class="col-3" for="BSB">Banking<br />BSB/Account</label>
		<div class="col-3">
			<input type="text" name="bank_bsb" id="BSB" class="form-control" placeholder="000 000"
				value="<?php print $this->data->bank_bsb ?>" />

		</div>

		<div class="col-4">
			<input type="text" name="bank_account" class="form-control" placeholder="00000 0000"
				value="<?php print $this->data->bank_account ?>" />

		</div>

	</div>

<?php if ( currentUser::isProgrammer() && $this->data->lockdown) {	?>
	<div class="form-group row py-1">
		<label class="col-3" for="paypal_ClientID">Paypal ClientID</label>
		<div class="col-9">
			<input type="text" name="paypal_ClientID" id="paypal_ClientID" class="form-control" placeholder="ClientID"
				value="<?php print $this->data->paypal_ClientID ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="paypal_ClientSecret">Paypal ClientSecret</label>
		<div class="col-9">
			<input type="text" name="paypal_ClientSecret" class="form-control" placeholder="Secret"
				value="<?php print $this->data->paypal_ClientSecret ?>" />

		</div>

	</div>

<?php }	?>

	<div class="row">
		<div class="offset-3 col-9">
			<input type="submit" name="action" value="update" class="btn btn-primary" />

		</div>

	</div>

<?php	}	?>

</form>
<script>
$(document).ready( function() {})
</script>
