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
<form class="form" method="post" action="<?php url::write( 'account') ?>">
	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Username</div>
		<div class="col-10 col-sm-4">
			<input type="text" class="form-control" placeholder="user name" value="<?php print currentUser::username() ?>" readonly />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Name</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="name" class="form-control" placeholder="name" value="<?php print currentUser::name() ?>" autofocus />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Email</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="email" class="form-control" placeholder="email" value="<?php print currentUser::email() ?>" <?php if ( !currentUser::isAdmin()) print 'readonly' ?> />

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-sm-2 col col-sm-10">
			<input type="submit" name="action" class="btn btn-primary" value="update" />

		</div>

	</div>

</form>

<div class="row py-1 mt-4">
	<div class="col-12 col-sm-2">Guids</div>
	<div class="col-12 col-sm-10">
<?php	if ( $this->data->guids) {	?>
		<table class="table table-striped">
			<tbody>
<?php	foreach ( $this->data->guids as $guid) {	?>
				<tr>
					<td guid-id><?php print $guid->id ?></td>
					<td guid-guid><?php print $guid->guid ?></td>
					<td>
						<select role="agreement-selector">
							<option></option>
<?php		foreach ( $this->data->agreements as $agreement) {	?>
							<option value="<?php print $agreement->id ?>"><?php print $agreement->agreement_id ?></option>

<?php		}	// foreach ( $this->data->agreements as $agreement)	?>

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

<div class="row py-1 mt-4">
	<div class="col-12 col-sm-2">Agreements</div>
	<div class="col-12 col-sm-10">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>agreement_id</td>
					<td>state</td>
					<td>refreshed</td>

				</tr>

			</thead>

			<tbody>
<?php	foreach ( $this->data->agreements as $agreement) {	?>
				<tr>
					<td><?php print $agreement->agreement_id ?></td>
					<td><?php print $agreement->state ?></td>
					<td><?php print date( \config::$DATE_FORMAT, strtotime( $agreement->refreshed)) ?></td>

				</tr>
<?php	}	// foreach ( $this->data->plans as $plan)	?>

			</tbody>

		</table>

	</div>

</div>

<?php	if ( $ag = $this->data->agreement) {
// sys::dump( $ag);
	?>
<div class="row py-1">
	<div class="col-12 col-sm-2">Agreement</div>
	<div class="col-12 col-sm-10">
		<table class="table table-striped">
			<tbody>
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

			</tbody>

		</table>

	</div>

</div>
<?php
			//~ sys::dump( $ag, NULL, FALSE);

		}	// if ( $this->data->agreement)
		else {
			// there is no active agreement	?>

<form class="form" method="post" action="<?php url::write( 'account') ?>">
	<div class="row py-1">
		<div class="col col-12 col-sm-2">Plans</div>
		<div class="col col-12 col-sm-10">
			<table class="table table-striped">
				<tbody>
<?php			foreach ( $this->data->plans as $plan) {	?>
					<tr>
						<td><input type="radio" name="plan_id" value="<?php print $plan->paypal_id ?>" /></td>
						<td><?php printf( '%s<br />%s', $plan->name, $plan->description ) ?></td>
						<td><?php print $plan->rate ?></td>
						<td><?php print $plan->frequency ?></td>

					</tr>
<?php			}	// foreach ( $this->data->plans as $plan)	?>

				</tbody>

			</table>

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-sm-2 col-12 col-sm-10">
			<input type="submit" name="action" class="btn btn-primary" value="subscribe" />

		</div>

	</div>

</form>

<?php
		}	?>

<div title="Update Easydose Plan" guid-plan-modal>
	<form class="form" method="post" action="<?php url::write('account') ?>" guid-plan-form>
		<input type="hidden" name="id" />
		<input type="hidden" name="plan_id" />
		<input type="hidden" name="action" value="update-plan-assignment" />
		<div class="container-fluid" modal>
			<div class="row">
				<div class="col col-3">
					guid

				</div>

				<div class="col col-9" guid></div>

			</div>

			<div class="row">
				<div class="col col-3">
					plan

				</div>

				<div class="col col-9" plan></div>

			</div>

		</div>

	</form>

</div>

<script>
$(document).ready( function() {
	$('select[role="agreement-selector"]').on('change', function() {
		var tr = $(this).closest('tr');
		var id = $('[guid-id]', tr).text();
		var guid = $('[guid-guid]', tr).text();

		var modal = $('[guid-plan-modal]');
		$('input[name="id"]', modal).val(id);
		$('input[name="plan_id"]', modal).val( $(this).val());
		$('[plan]', modal).html( $(':selected', this).text());
		$('[guid]', modal).html(guid);

		_brayworth_.modal.call( modal, {
			width: 600,
			buttons : {
				save : function( e) {
					$('[guid-plan-form]').submit();

				}

			}
		});

	})

});
</script>
