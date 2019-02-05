<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

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
					<td>id</td>
					<td>state</td>
					<td>expires</td>

				</tr>

			</thead>

			<tbody><?php
			foreach ( $this->data->invoices as $dto) { ?>
				<tr invoice data-id="<?= $dto->id ?>">
					<td><?= strings::asShortDate( $dto->created) ?></td>
					<td><?= $dto->id ?></td>
					<td><?= $dto->state ? $dto->state : 'created' ?></td>
					<td><?= strings::asShortDate( $dto->expires) ?></td>

				</tr>

			<?php } // while ( $dto = $this->data->invoices->dto())
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
