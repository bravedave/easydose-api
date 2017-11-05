<?php
/**
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
		<div class="col-2">Username</div>
		<div class="col-4">
			<input type="text" class="form-control" value="<?php print currentUser::username() ?>" readonly />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-2">Name</div>
		<div class="col-8">
			<input type="text" name="name" class="form-control" value="<?php print currentUser::name() ?>" autofocus />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-2">Email</div>
		<div class="col-8">
			<input type="text" name="email" class="form-control" value="<?php print currentUser::email() ?>" <?php if ( !currentUser::isAdmin()) print 'readonly' ?> />

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-2 col-10">
			<input type="submit" name="action" class="btn btn-primary" value="update" />

		</div>

	</div>

</form>

	<div class="row py-1">
		<div class="col-2">Agreements</div>
		<div class="col-10">
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
		<div class="col-2">Agreement</div>
		<div class="col-10">
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
					<div class="col-2">Plans</div>
					<div class="col-10">
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
					<div class="offset-2 col-10">
						<input type="submit" name="action" class="btn btn-primary" value="subscribe" />

					</div>

				</div>

			</form>

<?php
		}	?>

<script>
$(document).ready( function() {

});
</script>
