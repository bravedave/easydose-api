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

<div class="row">
	<div class="col">
		<table class="table table-sm">
			<tbody>
				<tr>
					<td>GUID</td>
					<td>
						<strong><?php print $dto->guid ?></strong>

					</td>

				</tr>

				<tr>
					<td>created</td>
					<td><?php print strings::asShortDate( $dto->created) ?></td>

				</tr>

				<tr>
					<td>use license</td>
					<td><?php print $dto->use_license ? 'yes' : '' ?></td>

				</tr>
				<tr>
					<td>Grace Product</td>
					<td><?php print $dto->grace_product ?></td>

				</tr>
				<tr>
					<td>Grace Wks</td>
					<td><?php if ((int)$dto->grace_workstations) print $dto->grace_workstations ?></td>

				</tr>
				<tr>
					<td>Grace Expires</td>
					<td><?php print strings::asShortDate( $dto->grace_expires) ?></td>

				</tr>

			</tbody>

			<tfoot>
				<tr>
					<td colspan="3" class="text-right">
						<a href="<?php url::write('guid/view/' . $dto->id) ?>" class="btn btn-outline-secondary">view</a>

					</td>

				</tr>

			</tfoot>

		</table>

	</div>

</div>

<?php }	// foreach ($this->data->guid as $dto)  ?>

	<script>
	$(document).ready( function() {
		// $('tr[invoice]').each( function( i, el) {
		// 	var _tr = $(el);
		// 	var id = _tr.data('id');
		//
		// 	_tr.addClass('pointer').on('click', function( e) {
		// 		window.location.href = _brayworth_.url('account/invoice/' + id);
		//
		// 	});
		//
		// });

	});
	</script>


<?php

	// sys::dump( $this->data->guid, NULL, FALSE);

	}
	else {
		print 'no invoices found';

	}	// if ( $this->data->license ) ?>
