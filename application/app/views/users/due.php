<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/	?>
<h1 class="d-none d-print-block"><?= $this->title ?></h1>

<table class="table table-sm">
	<thead class="small">
		<tr>
			<td colspan="4" class="border-0">&nbsp;</td>
			<td colspan="4" class="border-0 text-center">last invoice</td>

		</tr>

		<tr>
			<td class="border-0">proprietor</td>
			<td class="border-0">license</td>
			<td class="text-center border-0">wks</td>
			<td class="text-center border-0">expires</td>
			<td class="text-center border-0">no.</td>
			<td class="text-center border-0">state</td>
			<td class="text-center border-0">&nbsp;</td>
			<td class="text-center border-0">created</td>

		</tr>

	</thead>

	<tbody id="<?= $uidBody = uniqid('ed_') ?>">
	<?php
	if ( $this->data) {
		while ( $dto = $this->data->dto()) {	?>
		<tr data-id="<?= $dto->id ?>" <?php
			if ( $dto->due == dao\users::expired) {
				print 'class="bg-danger text-white"';

			}
			elseif ( $dto->due == dao\users::due) {
				print 'class="bg-warning"';

			}
			?>>
			<td><?= $dto->name ?></td>
			<td><?= $dto->license ?></td>
			<td class="text-center"><?= $dto->workstations ?></td>
			<td class="text-center"><?= strings::asShortDate( $dto->expires) ?></td>
			<td class="text-center"><?= $dto->last_invoice ?></td>
			<td class="text-center"><?= $dto->last_invoice_state ?></td>
			<td class="text-center"><?php
				if ( $dto->unpaid_invoice == dao\users::created) {
					print '...';

				}
				elseif ( $dto->unpaid_invoice == dao\users::sent) {
					print '<i class="fa fa-paper-plane-o"></i>';

				} ?></td>
			<td class="text-center"><?= strings::asShortDate( $dto->last_invoice_created) ?></td>

		</tr>

	<?php
		}	// while ( $dto = $this->data->dto())

	}	// if ( $this->data)	?>

	</tbody>

</table>
<script>
$(document).ready( function() {
	$('#<?= $uidBody ?> > tr').each( function( i, el) {
		$(el).addClass('pointer').on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();

			let _tr = $(this);
			let id = _tr.data('id');

			if ( e.ctrlKey) {
				window.open( _ed_.url('users/view/' + id));

			}
			else {
				window.location.href = _ed_.url('users/view/' + id);

			}

		});

	});

});
</script>