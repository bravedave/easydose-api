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
	<div class="form-group row">
		<label class="col-3" for="name">Name</label>
		<div class="col-9">
			<input type="text" name="name" class="form-control" autofocus
				value="<?php print $this->data->name ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="name">Address</label>
		<div class="col-9">
			<input type="text" name="street" class="form-control" placeholder="PO Box / street address" autocomplete="address-line1"
				value="<?php print $this->data->street ?>" />

		</div>

	</div>

	<div class="form-group row">
		<div class="offset-3 col-5">
			<input type="text" name="town" class="form-control" placeholder="town / suburb" autocomplete="address-level2"
				value="<?php print $this->data->town ?>" />

		</div>

	</div>

	<div class="form-group row">
		<div class="offset-3 col-3">
			<input type="text" name="state" class="form-control" placeholder="state" autocomplete="address-level3"
				value="<?php print $this->data->state ?>" />

		</div>

		<div class="col-2">
			<input type="text" name="postcode" class="form-control" placeholder="postcode" autocomplete="postal-code"
				value="<?php print $this->data->postcode ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="ABN">ABN</label>
		<div class="col-3">
			<input type="text" name="abn" id="ABN" class="form-control"
			 	placeholder="00 000 000 000"
				value="<?php print $this->data->abn ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="invoice_email">Invoice Email</label>
		<div class="col-9">
			<input type="text" name="invoice_email" id="invoice_email" class="form-control"
				placeholder="@"
				autocomplete="email"
				value="<?php print $this->data->invoice_email ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="bank_name">Banking</label>
		<div class="col-4">
			<input type="text" name="bank_name" id="BSB" class="form-control" placeholder="Bank Name"
				value="<?php print $this->data->bank_name ?>" />

		</div>

		<div class="col-2">
			<input type="text" name="bank_bsb" id="BSB" class="form-control"
			 placeholder="000 000" value="<?php print $this->data->bank_bsb ?>" />

		</div>

		<div class="col-3">
			<input type="text" name="bank_account" class="form-control"
				placeholder="00000 0000" value="<?php print $this->data->bank_account ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="bank_name">Invoicing</label>
		<div class="col-9">
			<div class="input-group">
				<input type="number" name="invoice_creation_days" class="form-control" min="0" max="30"
					placeholder="0" value="<?php print $this->data->invoice_creation_days ?>" />
				<div class="input-group-append">
					<div class="input-group-text">
						Invoice Lead Days

					</div>

				</div>

			</div>

			<div class="text-muted font-italic">
				The number of days before it is due for renewal that new invoice will be created

			</div>

		</div>

	</div>

	<div class="form-group row">
		<div class="offset-3 col-9">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" name="invoice_autosend" value="1"
					id="<?= $uid = strings::rand() ?>"
					<?php if ( $this->data->invoice_autosend) print 'checked'; ?> />
				<label class="form-check-label" for="<?= $uid ?>">Automatically send invoices when created</label>

			</div>

		</div>

	</div>

<?php if ( currentUser::isProgrammer() && $this->data->lockdown) {	?>
	<div class="form-group row py-1">
		<div class="offset-3 col-9">
			<div class="form-check">
				<input type="checkbox" name="paypal_live" id="paypal_live"
				 class="form-check-input" value="1"
				 <?php if ((int)$this->data->paypal_live) print 'checked'; ?> />

				<label class="form-check-label" for="paypal_live">Paypal is Live</label>

			</div>

		</div>

	</div>

	<div class="form-group row py-1">
		<label class="col-3" for="paypal_ClientID">Paypal ClientID</label>
		<div class="col-9">
			<input type="text" name="paypal_ClientID" id="paypal_ClientID"
			 class="form-control" placeholder="ClientID"
			 value="<?php print $this->data->paypal_ClientID ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-3" for="paypal_ClientSecret">Paypal ClientSecret</label>
		<div class="col-9">
			<input type="text" name="paypal_ClientSecret"
			 class="form-control" placeholder="Secret"
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
