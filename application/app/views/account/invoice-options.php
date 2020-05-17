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

	if ( currentUser::isAdmin()) {  ?>
<form class="form-group row d-print-none" id="<?= $uidFrmExclusion = strings::rand() ?>">
	<input type="hidden" name="invoice_id" value="<?= $this->data->invoice->id ?>" />
	<input type="hidden" name="action" value="license-exclusion" />
	<div class="col">
		<div class="form-check">
			<input type="checkbox" class="form-check-input" name="license_exclusion" value="1"
				id="<?= $uid = strings::rand() ?>"
				<?php if ($this->data->invoice->license_exclusion) print 'checked' ?> />

			<label class="form-check-label" for="<?= $uid ?>">
				Exclude from License Calculations

			</label>

		</div>

	</div>

</form>
<?php
	}	?>

<div class="row d-print-none">
	<div class="col">

	<?php	if ( !in_array( $this->data->invoice->state, ['approved', 'canceled']) && currentUser::id() == $this->data->invoice->user_id ) {  ?>
		<form class="d-inline-block" method="POST" action="<?php url::write('account') ?>">
			<input type="hidden" name="id" value="<?= $this->data->invoice->id ?>" />
			<input type="submit" name="action" class="btn btn-primary" value="pay invoice" />

		</form>

	<?php	}

			if ( currentUser::isAdmin()) {	?>

		<div class="btn-group btn-group-sm">
	<?php
				if ( !in_array( $this->data->invoice->state, ['canceled'])) {  ?>
			<a href="#" class="btn btn-outline-secondary" id="change-expiry">change expiry</a>
			<a href="#" class="btn btn-outline-secondary" id="override-workstations">override wks</a>

	<?php

					$earlier = new DateTime($this->data->invoice->state_changed);
					$later = new DateTime();
					$diff = $later->diff($earlier)->format("%a");

					// print $diff;
					/*
					* allow them to change the state for up to 180 days,
					* provided the state change was manual (paypal is
					* automatic)
					*/

					if ( !in_array( $this->data->invoice->state, ['approved', 'canceled']) || ($this->data->invoice->state_change == 'manual' && $diff < 180)) {  ?>
			<a href="#" class="btn btn-outline-secondary" id="change-state">change status</a>
			<a href="#" class="btn btn-outline-secondary" id="discount">discount</a>

	<?php
					}

				}

			} ?>


	<?php
			if ( !in_array( $this->data->invoice->state, ['approved', 'canceled'])) {  ?>
			<a class="btn btn-outline-secondary" href="<?php url::write( sprintf( 'account/invoice/%s?send=yes', $this->data->invoice->id )) ?>">send invoice</a>

	<?php
			}

			if ( currentUser::isAdmin()) {  ?>
			<a class="btn btn-outline-secondary" href="<?php url::write( sprintf( 'users/view/%s', $this->data->invoice->user_id )) ?>">account</a>
			<a href="#" id="authoritative-invoice" class="btn btn-outline-secondary"><?php
					if ( $this->data->invoice->authoritative) print '<i class="fa fa-fw fa-check"></i>';
					?>authoritative</a>

		</div>
	<?php
			} ?>


	</div>

</div>
<?php if ( currentUser::isAdmin()) {  ?>
<script>
$(document).ready( function() {
	$('#change-expiry').on( 'click', function( e) {
		e.preventDefault();
		$(this).blur();

		var fld = $('<input type="date" class="form-control" value="<?php print $this->data->invoice->expires ?>" />')
		_brayworth_.modal({
			title : 'change expiry date',
			text : fld,
			width : 300,
			buttons : {
				update : function() {
					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : {
							action : 'update-expires',
							invoice_id : <?php print $this->data->invoice->id ?>,
							expires : fld.val()

						}

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					});

				}

			}

		});

	});

	$('#override-workstations').on( 'click', function( e) {
		e.preventDefault();
		$(this).blur();

		let fld = $('<input type="number" class="form-control" value="<?php print $this->data->invoice->workstation_override ?>" />');

		_brayworth_.modal({
			title : 'Workstation Override',
			text : fld,
			width : 300,
			buttons : {
				update : function() {
					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : {
							action : 'update-workstation_override',
							invoice_id : <?php print $this->data->invoice->id ?>,
							workstations : fld.val()

						}

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					});

				}

			}

		});

	});

	$('#change-state').on( 'click', function( e) {
		e.preventDefault();
		$(this).blur();

		let fld = $('<select class="form-control"></select>');
		$('<option />').appendTo( fld);
		$('<option value="approved">approved</option>').appendTo( fld);
		$('<option value="provisional">provisional</option>').appendTo( fld);
		$('<option value="canceled">canceled</option>').appendTo( fld);

		let fg = $('<div class="form-group"></div>').append( fld);

		_brayworth_.modal({
			title : 'change invoice state',
			text : fg,
			width : 300,
			buttons : {
				update : function() {
					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : {
							action : 'update-state',
							invoice_id : <?php print $this->data->invoice->id ?>,
							state : fld.val()

						}

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					})

				}

			}

		})

		$(this).blur();

	});

	$('#discount').on( 'click', function( e) {
		e.preventDefault();

		let reason = $('<input type="text" class="form-control" placeholder="reason" value="<?php print $this->data->invoice->discount_reason ?>" />');
		let discount = $('<input type="number" class="form-control text-right" value="<?php print $this->data->invoice->discount ?>" />');
		let wrap = $('<div class="row"></div>');
		let col = $('<div class="col-7"></div>').append(reason).appendTo(wrap);
		$('<div class="col-5"></div>').append(discount).appendTo(wrap);

		$('<a href="#" class="small">pro rata</a>').on( 'click', function( e) {
			e.preventDefault();
			reason.val( 'pro rata discount');

		}).appendTo(col);

		_brayworth_.modal({
			title : 'Pre GST Discount',
			text : wrap,
			buttons : {
				update : function() {
					let data = {
						action : 'apply-discount',
						invoice_id : <?php print $this->data->invoice->id ?>,
						reason : reason.val(),
						discount : Number( discount.val())

					};

					if ( isNaN( data.discount)) data.discount = 0;
					if ( data.discount < 0) data.discount = 0;

					if ( data.discount > 0 && data.reason.trim() =='') {
						_brayworth_.growlError( 'please enter a reason');
						reason.focus();
						return;

					}

					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : data

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					});

				}

			}

		});

	});

	$('#authoritative-invoice').on( 'click', function( e) {
		e.preventDefault();

		_brayworth_.modal({
			title : 'Authoritative Invoice',
			text : 'Make this the Authoritative Invoice, the license will<br />ignore all previous invoices',
			buttons : {
				yes : function() {
					let data = {
						action : 'make-authoritative',
						invoice_id : <?php print $this->data->invoice->id ?>,
						value : 1

					};

					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : data

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					});

				},
				no : function() {
					let data = {
						action : 'make-authoritative',
						invoice_id : <?php print $this->data->invoice->id ?>,
						value : 0

					};

					hourglass.on();
					this.modal('close');

					_brayworth_.post({
						url : _brayworth_.url('invoices'),
						data : data

					})
					.then( function( d) {
						_brayworth_.growl(d).then( function() {
							window.location.reload();
							hourglass.off();

						});

					});

				}

			}

		});

	});

	$('input[name="license_exclusion"]').on( 'change', function( e) {
		$(this).closest('form').submit();

	});

<?php	if ( currentUser::isAdmin()) {  ?>
	$('#<?= $uidFrmExclusion ?>').on( 'submit', function( e) {
		let data = $(this).serializeFormJSON();
		//~ console.log( data);

		_brayworth_.post({
			url : _brayworth_.url( 'invoices'),
			data : data

		}).then( _brayworth_.growl);

		return false;

	});

<?php	}	// if ( currentUser::isAdmin())  ?>

});
</script>
<?php } ?>
