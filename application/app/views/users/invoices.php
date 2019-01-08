<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h6 class="m-0">invoices</h6>

<?php
	if ( $this->data->invoices ) { ?>

	<table class="table table-sm">
		<colgroup>
			<col span="3" style="width: 33%;" />

		</colgroup>

		<thead class="small">
			<tr>
				<td>id</td>
				<td>state</td>
				<td class="text-center">authoritative</td>
				<td>created</td>
				<td>expires</td>

			</tr>

		</thead>

		<tbody>
			<?php foreach ($this->data->invoices as $dto) {  ?>
				<tr data-id="<?php print $dto->id ?>" invoice>
					<td><?= $dto->id ?></td>
					<td><?= $dto->state ?></td>
					<td class="text-center"><?= $dto->authoritative ? strings::html_tick : '' ?></td>
					<td><?= strings::asLocalDate( $dto->created) ?></td>
					<td><?= strings::asLocalDate( $dto->expires) ?></td>

				</tr>

			<?php }	// foreach ($this->data->invoices as $dto)  ?>

		</tbody>

		<?php if ( $this->data->license ) { ?>
			<tfoot>
				<tr>
					<td class="text-right" colspan="4">license expires:</td>
					<td><?= strings::asLocalDate( $this->data->license->expires) ?></td>

				</tr>

			</tfoot>

		<?php }	// if ( $this->data->license ) ?>

	</table>

	<script>
	$(document).ready( function() {
		$('tr[invoice]').each( function( i, el) {
			var _tr = $(el);
			var id = _tr.data('id');

			_tr.addClass('pointer').on('click', function( e) {
				window.location.href = _brayworth_.url('account/invoice/' + id);

			});

		});

	});
	</script>


<?php

// sys::dump( $this->data->invoices, NULL, FALSE);

	}
	else {
		print 'no invoices found';

	}	// if ( $this->data->license ) ?>
