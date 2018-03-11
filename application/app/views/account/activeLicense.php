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
<div class="row py-1 mt-2">
	<div class="col-12 col-lg-2">
		<strong>Active Licenses(s)</strong>
	</div>
	<div class="col-12 col-lg-10">
    <table class="table table-striped table-sm">
      <tbody>
<?php
    // sys::dump( $this->data->agreement);

    foreach ( [$this->data->license->license, $this->data->license->workstation] as $ag) {
      if ( $ag) {	?>

				<?php
					// print '<tr><td colspan="2">';
					// sys::dump( $ag, NULL, FALSE);
					// print '</td></tr>';

				?>

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
					<td><?php print $ag->payment_id ?></td>

				</tr>

				<tr>
					<td>State</td>
					<td><?php print $ag->state ?></td>

				</tr>

				<tr>
					<td>Term</td>
					<td><?php print $ag->term ?></td>

				</tr>

        <tr>
					<td>Created</td>
					<td><?php print strings::asShortDate( $ag->created) ?></td>

				</tr>

<?php
			}

   }	?>

      </tbody>

    </table>

  </div>

</div>
