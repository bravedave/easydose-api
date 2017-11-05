<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="row py-1">
	<div class="col-2">ID</div>
	<div class="col-8"><?php print $this->data->plan->id ?></div>

</div>

<div class="row py-1">
	<div class="col-2">State</div>
	<div class="col-8"><?php print $this->data->plan->state ?></div>

</div>

<div class="row py-1">
	<div class="col-2">Name</div>
	<div class="col-8"><?php print $this->data->plan->name ?></div>

</div>

<div class="row py-1">
	<div class="col-2">Description</div>
	<div class="col-8"><?php print $this->data->plan->description ?></div>

</div>

<div class="row py-1">
	<div class="col-2">Type</div>
	<div class="col-8"><?php print $this->data->plan->type ?></div>

</div>

<?php
	$defs = $this->data->plan->payment_definitions;
	if ( count( $defs)) {	?>
<div class="row py-1">
	<div class="col-2">Payment Definitions</div>
	<div class="col-10">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>name</td>
					<td>type</td>
					<td>frequency</td>
					<td>currency</td>
					<td>value</td>
					<td>id</td>

				</tr>

			</thead>
			<tbody>
<?php	foreach ( $defs as $def) {	?>
				<tr>
					<td><?php print $def->name ?></td>
					<td><?php print $def->type ?></td>
					<td><?php print $def->frequency ?></td>
					<td><?php print $def->amount->currency ?></td>
					<td><?php print number_format( $def->amount->value, 2) ?></td>
					<td><?php print $def->id ?></td>

				</tr>

<?php	}	// foreach ( $defs as $def)	?>

			</tbody>

		</table>

	</div>

</div>
<?php	}	// if ( count( $links)) {	?>

<?php
	$links = [];
	foreach( $this->data->plan->links as $link)
		$links[] = $link->href;

	if ( count( $links)) {	?>
<div class="row py-1">
	<div class="col-2">Plan Links</div>
	<div class="col-10">
		<?php printf( '<ul><li>%s</li></ul>', implode( '</li><li>', $links)) ?>
	</div>

</div>
<?php	}	// if ( count( $links)) {	?>

<div class="row py-1">
	<div class="col-2">Created</div>
	<div class="col-8"><?php print date( 'd-M-y', strtotime( $this->data->plan->create_time)) ?></div>

</div>

<div class="row py-1">
	<div class="col-2">Updated</div>
	<div class="col-8"><?php print date( 'd-M-y', strtotime( $this->data->plan->update_time)) ?></div>

</div>

