<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<h6 class="m-0">
		SITES

	</h6>

<?php if ( count( $this->data->sites)) {	?>
	<table class="table table-striped table-sm">
		<thead>

		</thead>

		<tbody>
			<?php foreach ($this->data->sites as $site) {	?>
				<tr data-id="<?php print $site->id ?>" site>
					<td><?php print $site->site ?></td>
					<td><?php print $site->workstation ?></td>
					<td><?php print $site->state ?></td>
					<td class="text-right"><?php printf( '%s/%s', $site->patientsActive, $site->patients) ?></td>

				</tr>

			<?php }	// foreach ($this->data->sites as $site)  ?>

		</tbody>

	</table>

	<script>
	$(document).ready( function() {
		$('tr[site]').each( function( i, tr) {
	    var _tr = $(tr);
	    var id = _tr.data( 'id');

	    _tr
	    .addClass('pointer')
	    .on( 'click', function( e) {
	      window.location.href = _brayworth_.url('sites/view/'+id);

	    });

		});

	});
	</script>

<?php

	// sys::dump( $this->data->sites, NULL, FALSE);

	}
	else {
		print 'no sites found';

	}	// if ( $this->data->sites ) ?>
