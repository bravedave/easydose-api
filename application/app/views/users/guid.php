<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="row">
	<div class="col">
		<h6>
			Pharmacy Database

		</h6>

	</div>

</div>

<?php
	if ( $this->data->guid ) {
		foreach ($this->data->guid as $dto) {
			// will only be 1	?>

<div class="form-group row">
	<div class="col-sm-3 col-form-label">GUID</div>
	<div class="col">
		<div class="row">
			<div class="col">
				<div class="input-group">
					<input type="text" class="form-control font-weight-bold"
						readonly
						value="<?= $dto->guid ?>" />
					<div class="input-group-append">
						<div class="input-group-text">
							<a href="<?= strings::url('guid/view/' . $dto->id) ?>" title="view">
								<i class="fa fa-external-link"></i>

							</a>

						</div>

					</div>

					<div class="input-group-append">
						<button class="btn btn-outline-primary"
							type="button"
							id="<?= $uid = strings::rand() ?>">check</button>

					</div>

				</div>

			</div>

		</div>

		<div class="row"><div class="col" id="<?= $uid ?>_result"></div></div>

	</div>

</div>
<script>
$(document).ready( function() {
	$('#<?= $uid ?>').on( 'click', function( e) {

		_brayworth_.post({
			url : _brayworth_.url('api'),
			data : {
				action : 'get-license',
				guid : "<?= $dto->guid ?>"

			},

		}).then( function( d) {
			_brayworth_.growl( d);
			console.table( d);
			let tb = $('<tbody />');
			$.each( d, function( k, v) {
				$('<tr />')
				.append( $('<td />').html(k))
				.append( $('<td />').html(v))
				.appendTo( tb);

			})

			$('#<?= $uid ?>_result').html('').addClass('p-4');


			$('<table class="table table-success table-striped" />')
			.append('<thead class="font-weight-bold"><tr><td colspan="2">Server Response</td></tr></thead>')
			.append( tb)
			.appendTo('#<?= $uid ?>_result');

		});

	});

});

</script>

<div class="form-group row">
	<div class="col-sm-3 col-form-label">created</div>
	<div class="col-sm-3">
		<input type="text" class="form-control-plaintext" readonly
			value="<?= strings::asShortDate( $dto->created) ?>" />

		</div>

</div>

<?php
// if ( true) {
if ( $dto->grace_product) { ?>

<div class="form-group row">
	<div class="col-sm-3 col-form-label">Grace Product</div>
	<div class="col">
		<div class="row">
			<div class="col-sm-5">
				<input type="text" class="form-control-plaintext" readonly
					value="<?= $dto->grace_product ?>" />

			</div>

			<div class="col-sm-3">
				<input type="text" class="form-control-plaintext" readonly
					value="<?php if ((int)$dto->grace_workstations) printf( '%s wks', $dto->grace_workstations) ?>" />

			</div>

			<div class="col-sm-3">
				<input type="text" class="form-control-plaintext" readonly
					value="Exp: <?= strings::asShortDate( $dto->grace_expires) ?>" />

			</div>

		</div>

	</div>

</div>

<?php	}	// if ( $dto->grace_product) ?>

<div class="row">
	<div class="col-sm-2"></div>
	<div class="col"></div>

</div>

<?php
		}	// foreach ($this->data->guid as $dto)

		// sys::dump( $this->data->guid, NULL, FALSE);

	}
	else {
		print 'no invoices found';

	}	// if ( $this->data->license ) ?>
