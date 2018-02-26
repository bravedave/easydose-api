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
	<div class="col col-12 col-lg-2">
		<strong>Guids</strong>
	</div>
	<div class="col col-12 col-lg-10">
<?php	if ( $this->data->guids) {	?>
		<table class="table table-striped table-sm small">
			<thead>
				<tr>
					<td class="d-none d-lg-table-cell">#</td>
					<td>GUID</td>
					<td>Created</td>
					<td>Updated</td>
				</tr>

			</thead>
			<tbody>
<?php	foreach ( $this->data->guids as $guid) {	?>
				<tr>
					<td guid-id class="d-none d-lg-table-cell"><?php print $guid->id ?></td>
					<td guid-guid><?php print $guid->guid ?></td>
					<td><?php print date(\config::$DATE_FORMAT, strtotime( $guid->created)) ?></td>
					<td><?php print date(\config::$DATE_FORMAT, strtotime( $guid->updated)) ?></td>

				</tr>
<?php	}	// foreach ( $this->data->guids as $guid)	?>

			</tbody>

		</table>

<?php	}
			else {	?>

		<p>
			None found : guids will show here if you register your easydose software
		</p>

<?php	}	// if ( $this->data->guids)

			// sys::dump( $this->data->guids, NULL, FALSE);

			?>

	</div>

</div>
