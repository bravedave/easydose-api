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

	*/	?>
<div class="row py-1 mt-4">
	<div class="col-12 col-lg-2">
		<i class="fa fa-fw fa-caret-right pointer pull-right" id="show-active-agreements"></i>
		<div>
			Active<br />
			Agreement(s)

		</div>

	</div>
	<div class="col-12 col-lg-10">
		<span id="show-active-agreements-table-ellipses" class="pointer">...</span>
		<table class="table table-striped table-sm d-none" id="show-active-agreements-table">
			<tbody>
<?php
		// sys::dump( $this->data->agreement);

		foreach ( [$this->data->license->license, $this->data->license->workstation] as $ag) {
			if ( $ag) {	?>
				<tr>
					<td>Product</td>
					<td>
						<strong>
							<?php printf( '%s : %s', $ag->product, $ag->productDescription) ?>
						</strong>

					</td>

				</tr>

				<tr>
					<td>ID</td>
					<td><?php print $ag->agreement_id ?></td>

				</tr>

				<tr>
					<td>Description</td>
					<td><?php print $ag->description ?></td>

				</tr>

				<tr>
					<td>State</td>
					<td><?php print $ag->state ?></td>

				</tr>

				<tr>
					<td>payment_method</td>
					<td><?php print $ag->payment_method ?></td>

				</tr>

				<tr>
					<td>name</td>
					<td><?php print $ag->name ?></td>

				</tr>

				<tr>
					<td>start_date</td>
					<td><?php print date( \config::$DATE_FORMAT, strtotime( $ag->start_date)) ?></td>

				</tr>

				<tr>
					<td>next_billing_date</td>
					<td><?php print date( \config::$DATE_FORMAT, strtotime( $ag->next_billing_date)) ?></td>

				</tr>

				<tr>
					<td>cycles_completed</td>
					<td><?php print $ag->cycles_completed ?></td>

				</tr>

				<tr>
					<td>frequency</td>
					<td><?php print $ag->frequency ?></td>

				</tr>

				<tr>
					<td>amount</td>
					<td><?php print $ag->value ?></td>

				</tr>

				<tr>
					<td>refreshed</td>
					<td><?php print date( \config::$DATE_FORMAT, strtotime( $ag->refreshed)) ?></td>

				</tr>

<?php
			}

		}	?>

			</tbody>

		</table>

	</div>

</div>
<script>
$(document).ready( function() {
	$('#show-active-agreements, #show-active-agreements-table-ellipses').on( 'click', function(e) {
		var t = $('#show-active-agreements-table');
		if ( t.hasClass( 'd-none')) {
			t.removeClass( 'd-none');
			$('#show-active-agreements-table-ellipses').addClass( 'd-none');
			$('#show-active-agreements').removeClass('fa-caret-right').addClass('fa-caret-down');

		}
		else {
			t.addClass( 'd-none');
			$('#show-active-agreements-table-ellipses').removeClass( 'd-none');
			$('#show-active-agreements').removeClass('fa-caret-down').addClass('fa-caret-right');

		}

	})

})
</script>
