<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-striped">
	<thead>
		<tr>
			<td>ID</td>
			<td>PayPal ID</td>
			<td>Name</td>
			<td>Description</td>
			<td>State</td>
			<td>Rate</td>
			<td>Frequency</td>

		</tr>

	</thead>

	<tbody>
<?php   while ( $dto = $this->data->plans->dto()) { ?>
    <tr data-planid="<?php print $dto->paypal_id ?>" plan>
      <td><?php print $dto->id ?></td>
      <td><?php print $dto->paypal_id ?></td>
      <td><?php print $dto->name ?></td>
      <td><?php print $dto->description ?></td>
      <td><?php print $dto->state ?></td>
      <td><?php print $dto->rate ?></td>
      <td><?php print $dto->frequency ?></td>
    </tr>

<?php   } // while ( $dto = $this->data->plans0>dto())  ?>

  </tbody>

</table>

<div class="row py-1">
  <div class="col">
    [<a href="<?php url::write('settings/newplan') ?>">new plan</a>]
    [<a href="<?php url::write('settings/plans/created') ?>">created plans</a>]
    [<a href="<?php url::write('settings/plans/active') ?>">active plans</a>]
    [<a href="<?php url::write('settings/plans/inactive') ?>">inactive plans</a>]
    &nbsp;
    [<a href="<?php url::write('settings/plans/') ?>">read from paypal</a>]

  </div>

</div>
<script>
$(document).ready( function() {
  $('tr[plan]').each( function( i, row) {
    var _row = $(row);

    _row.on( 'click', function( e) {
      e.stopPropagation(); e.preventDefault();
      _brayworth_.urlwrite( 'settings/plan/' + _row.data('planid'));

    })

  })


})
</script>
