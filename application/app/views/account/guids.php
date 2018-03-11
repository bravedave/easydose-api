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
	<div class="col col-12 col-lg-2">
<?php	if ( $this->data->guids) {	?>
		<i class="fa fa-fw fa-caret-right pointer pull-right" id="show-user-guids"></i>
<?php	}	// if ( $this->data->guids)	?>
		Guids
	</div>
	<div class="col col-12 col-lg-10">
<?php	if ( $this->data->guids) {	?>
		<span id="show-user-guids-table-ellipses" class="pointer">...</span>
		<table class="table table-striped table-sm d-none" id="show-user-guids-table">
			<colgroup>
					<col />
					<col />
					<col style="width: 7em"/>
					<col style="width: 7em"/>
			</colgroup>

			<thead>
				<tr>
					<td class="d-none d-lg-table-cell">#</td>
					<td>GUID</td>
					<td class="text-center">Created</td>
					<td class="text-center">Updated</td>
				</tr>

			</thead>
			<tbody>
<?php	foreach ( $this->data->guids as $guid) {	?>
				<tr>
					<td guid-id class="d-none d-lg-table-cell"><?php print $guid->id ?></td>
					<td guid-guid><?php print $guid->guid ?></td>
					<td class="text-center"><?php print strings::asShortDate( $guid->created) ?></td>
					<td class="text-center"><?php print strings::asShortDate( $guid->updated) ?></td>

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
<script>
$(document).ready( function() {
	$('#show-user-guids, #show-user-guids-table-ellipses').on( 'click', function(e) {
		var t = $('#show-user-guids-table');
		if ( t.hasClass( 'd-none')) {
			t.removeClass( 'd-none');
			$('#show-user-guids-table-ellipses').addClass( 'd-none');
			$('#show-user-guids').removeClass('fa-caret-right').addClass('fa-caret-down');

		}
		else {
			t.addClass( 'd-none');
			$('#show-user-guids-table-ellipses').removeClass( 'd-none');
			$('#show-user-guids').removeClass('fa-caret-down').addClass('fa-caret-right');

		}

	})

})
</script>
