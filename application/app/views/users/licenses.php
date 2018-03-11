<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h6 class="m-0">
		licenses

	</h6>

<?php
	if ( $this->data->license && ( 'active' == $this->data->license->state)) {
		// $license = $this->data->license;
		// sys::dump( $this->data->license, NULL, FALSE);
 ?>

		<table class="table table-striped table-sm">
			<thead>
				<tr>
					<td>description</td>
					<td>state</td>
					<td>wks</td>
					<td>expires</td>

				</tr>

			</thead>

			<tbody>
				<tr>
					<td>
						<?php printf( '%s<br />%s',
							$this->data->license->description,
							$this->data->license->product ) ?>

					</td>

					<td>
						<?php print $this->data->license->state ?>

					</td>

					<td>
						<?php print $this->data->license->workstations ?>

					</td>

					<td>
						<?php print strings::asLocalDate( $this->data->license->expires) ?>

					</td>

				</tr>

			</tbody>

		</table>

<?php

		// sys::dump( $this->data->license, NULL, FALSE);

	}
	else {
		print 'no license found';

	}	// if ( $this->data->license ) ?>
