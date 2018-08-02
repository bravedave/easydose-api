<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

if ( isset( $this->data->plans->plans)) {
	foreach ( $this->data->plans->plans as $plan) {
		$links = [];
		foreach( $plan->links as $link)
			$links[] = $link->href;

		?>
<form class="form" method="post" action="<?php url::write( 'settings') ?>">

	<input type="hidden" name="plan_id" value="<?php print $plan->id ?>" />
	<input type="hidden" name="confirmed" value="no" />
	<input type="hidden" name="action" />

	<table class="table">
		<thead>
			<tr>
				<td colspan="4"><?php printf( '%s<p>%s</p>', $plan->name, $plan->description) ?></td>

			</tr>

			<tr>
				<td>created</td>
				<td>updated</td>
				<td>state</td>
				<td>type</td>

			</tr>

		</thead>

		<tbody>
			<tr>
				<td><?php print date( 'd-M-y', strtotime( $plan->create_time)) ?></td>
				<td><?php print date( 'd-M-y', strtotime( $plan->update_time)) ?></td>
				<td><?php print $plan->state ?></td>
				<td><?php print $plan->type ?></td>

			</tr>

			<tr>
				<td>Plan ID</td>
				<td colspan="3"><?php print $plan->id ?></td>

			</tr>

<?php	if ( count( $links)) {	?>
			<tr>
				<td>Plan Links</td>
				<td colspan="3"><?php printf( '<ul><li>%s</li></ul>', implode( '</li><li>', $links)) ?></td>

			</tr>
<?php	}	// if ( count( $links)) {	?>

			<tr>
				<td colspan="4">
					<a href="<?php url::write( 'settings/plan/' . urlencode( $plan->id )) ?>" class="btn btn-raised">view plan</a>
					<button class="btn btn-danger" data-role="delete plan">delete plan</button>
<?php	if ( $plan->state == 'ACTIVE') {	?>
					<button class="btn btn-raised" data-role="de-activate plan">de-activate plan</button>
<?php	}
		else {	?>
					<button class="btn btn-raised" data-role="activate plan">activate plan</button>

<?php	}	// if ( $plan->state != 'ACTIVE')	?>

				</td>

			</tr>

		</tbody>

	</table>

<?php
		//~ if ( TRUE) {
		if ( FALSE) {
		?>
	<div class="row py-1">
		<div class="col">
			<?php sys::dump( $plan, NULL, FALSE); ?>

		</div>

	</div>
<?php
		}	// if ( FALSE)	?>
</form>


<?php
	}	// foreach ( $this->data->plans as plan)

}	// if ( isset( $this->data->plans->plans)
else {	?>
	<div class="row py-1">
		<div class="col">
			no plans found

		</div>

	</div>

<?php
}	?>
	<div class="row py-1">
		<div class="col">
			[<a href="<?php url::write('settings/newplan') ?>">new plan</a>]
			[<a href="<?php url::write('settings/plans/created') ?>">created plans</a>]
			[<a href="<?php url::write('settings/plans/active') ?>">active plans</a>]
			[<a href="<?php url::write('settings/plans/inactive') ?>">inactive plans</a>]

		</div>

	</div>

<script>
$(document).ready( function() {
	$('button[data-role="activate plan"]').each( function( i, el) {
		var _el = $(el);
		var _form = _el.closest( 'form');

		_el.on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();
			//~ $('body').growl( 'oh yeah');

			$('input[name="confirmed"]', _form).val('yes');
			$('input[name="action"]', _form).val('activate plan');
			_form.submit();

		})

	});

	$('button[data-role="de-activate plan"]').each( function( i, el) {
		var _el = $(el);
		var _form = _el.closest( 'form');

		_el.on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();
			//~ $('body').growl( 'oh yeah');

			$('input[name="confirmed"]', _form).val('yes');
			$('input[name="action"]', _form).val('de-activate plan');
			_form.submit();

		})

	});

	$('button[data-role="delete plan"]').each( function( i, el) {
		var _el = $(el);
		var _form = _el.closest( 'form');

		_form.on( 'submit', function() {
			if ( $('input[name="confirmed"]', _form).val() == 'yes')
				return ( true);

			$('body').growl( 'invalid submission');
			return ( false);

		})

		_el.on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();

			_brayworth_.modal({
				title : 'Confirm',
				text : 'Are you sure ?',
				buttons : {
					no : function() {
						this.modal('close');

					},
					yes : function() {
						this.modal('close');
						$('input[name="confirmed"]', _form).val('yes');
						$('input[name="action"]', _form).val('delete plan');
						_form.submit();

					},

				}

			});

		})

	});

});
</script>
