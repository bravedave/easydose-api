<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

	if ( $this->data->invoices) {

		// sys::dump( $this->data->invoices);
	?>
<div class="row py-1 mt-2">
	<div class="col-12 col-lg-2">
		Invoices..

	</div>

	<div class="col-12 col-lg-8">
		<table class="table table-sm" id="user-invoices-table">
			<thead class="small">
				<tr>
					<td>date</td>
					<td class="text-center">id</td>
					<td class="text-center">state</td>
					<td class="text-center">expires</td>
					<td></td>

				</tr>

			</thead>

			<tbody><?php
			foreach ( $this->data->invoices as $dto) { ?>
				<tr invoice data-id="<?= $dto->id ?>">
					<td><?= strings::asShortDate( $dto->created) ?></td>
					<td class="text-center"><?= $dto->id ?></td>
					<td class="text-center"><?= $dto->state ? $dto->state : 'created' ?></td>
					<td class="text-center"><?= strings::asShortDate( $dto->expires) ?></td>

			<?php if ( !in_array( $dto->state, ['approved', 'canceled']) && currentUser::id() == $dto->user_id ) {  ?>
					<td class="text-right">
						<a class="btn btn-primary" href="<?= strings::url( 'account/invoice/' . $dto->id) ?>">View &amp; Pay</a>

					</td>
			<?php } else { print '<td>&nbsp;</td>'; }	?>

				</tr>

			<?php
			} // while ( $dto = $this->data->invoices->dto())
			?></tbody>

		</table>

	</div>

</div>
<script>
$(document).ready( function() {
	$('tr[invoice]', '#user-invoices-table').each( function( i, tr) {
		let _tr = $(tr);
		let id = _tr.data('id');

		_tr.addClass('pointer').on( 'click', function( e) {
			window.location.href = _brayworth_.url('account/invoice/' + id);

		});

	});

});
</script>
<?php
	}	// if ( $this->data->invoices)
