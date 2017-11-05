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
		<label class="col-2" for="name">Name</label>
		<div class="col-8">
			<input type="text" name="name" class="form-control" autofocus
				value="<?php print $this->data->name ?>" />

		</div>

	</div>

	<div class="row">
		<div class="form-check offset-2 col-4">
			<label class="form-check-label">
				<input type="checkbox" name="lockdown" class="form-check-input" value="1"
					<?php if( $this->data->lockdown) print 'checked'; ?> />

				Lockdown

			</label>

		</div>

	</div>

<?php if( $this->data->lockdown) {	?>
	<div class="form-group row">
		<label class="col-2" for="paypal_ClientID">Paypal ClientID</label>
		<div class="col-8">
			<input type="text" name="paypal_ClientID" id="paypal_ClientID" class="form-control" placeholder="ClientID"
				value="<?php print $this->data->paypal_ClientID ?>" />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="paypal_ClientSecret">Paypal ClientSecret</label>
		<div class="col-8">
			<input type="text" name="paypal_ClientSecret" class="form-control" placeholder="Secret"
				value="<?php print $this->data->paypal_ClientSecret ?>" />

		</div>

	</div>

<?php }	?>

	<div class="row">
		<div class="offset-2 col-4">
			<input type="submit" name="action" value="update" class="btn btn-primary" />

		</div>

	</div>

<?php	}	?>

</form>
<script>
$(document).ready( function() {})
</script>