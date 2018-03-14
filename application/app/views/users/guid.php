<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h6 class="m-0">
		GUID

	</h6>

<?php
	if ( $this->data->guid ) {
		foreach ($this->data->guid as $dto) {
	  	// will only be 1	?>
			<table class="table table-striped table-sm">
				<thead>
					<tr>
						<td colspan="3">
							<strong>
								<?php print $dto->guid ?>

							</strong>

						</td>

					</tr>

					<tr>
						<td>created</td>
						<td>use</td>
						<td>Grace</td>

					</tr>

				</thead>

				<tbody>
					<tr>
						<tr>
							<td><?php print strings::asShortDate( $dto->created) ?></td>
							<td><?php print $dto->use_license ? 'yes' : '' ?></td>
							<td class="p-0">
								<table class="table table-sm">
									<tr>
										<td>Product</td>
										<td><?php print $dto->grace_product ?></td>

									</tr>
									<tr>
										<td>Grace Wks</td>
										<td><?php print $dto->grace_workstations ?></td>

									</tr>
									<tr>
										<td>Grace Expires</td>
										<td><?php print strings::asShortDate( $dto->grace_expires) ?></td>

									</tr>

								</table>

							</td>

						</tr>

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
