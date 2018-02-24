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
					<td>Agreement</td>
					<td>Created</td>
					<td>Updated</td>
				</tr>

			</thead>
			<tbody>
<?php	foreach ( $this->data->guids as $guid) {	?>
				<tr>
					<td guid-id class="d-none d-lg-table-cell"><?php print $guid->id ?></td>
					<td guid-guid><?php print $guid->guid ?></td>
					<td>
						<select role="agreement-selector">
							<option></option>
<?php		foreach ( $this->data->agreementsForUser as $agreement) {	?>
							<option value="<?php print $agreement->id ?>"
								<?php if ( $guid->agreements_id == $agreement->id) print 'selected'; ?>
								><?php print $agreement->agreement_id ?></option>

<?php		}	// foreach ( $this->data->agreementsForUser as $agreement)	?>

						</select>

					</td>
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
<div class="d-none">
	<div title="Update Easydose Plan" guid-plan-modal>
		<form class="form" method="post" action="<?php url::write('account') ?>" guid-plan-form>
			<input type="hidden" name="id" />
			<input type="hidden" name="agreements_id" />
			<input type="hidden" name="action" value="update-agreement-assignment" />
			<div class="container-fluid" modal>
				<div class="row">
					<div class="col col-3 py-2">guid</div>
					<div class="col col-9 py-2" guid></div>

				</div>

				<div class="row">
					<div class="col col-3 py-2">plan</div>
					<div class="col col-9 py-2" agreement></div>

				</div>

			</div>

		</form>

	</div>

</div>
<script>
$(document).ready( function() {
	$('select[role="agreement-selector"]').on('change', function() {
		if ( Number( $(this).val())) {
			var tr = $(this).closest('tr');
			var id = $('[guid-id]', tr).text();
			var guid = $('[guid-guid]', tr).text();

			var modal = $('[guid-plan-modal]');
			$('input[name="id"]', modal).val(id);
			$('input[name="agreements_id"]', modal).val( $(this).val());
			$('[agreement]', modal).html( $(':selected', this).text());
			$('[guid]', modal).html(guid);

			_brayworth_.modal.call( modal, {
				width: 600,
				buttons : {
					apply : function( e) {
						$('[guid-plan-form]').submit();

					}

				}

			});

		}

	});

});
</script>
