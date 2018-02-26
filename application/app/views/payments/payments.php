<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

  // sys::dump( $this->data->res);

  	?>
<table class="table table-striped table-sm">
  <thead>
    <tr>
      <td>state</td>
      <td>description</td>
      <td>tax</td>
      <td>value</td>
      <td>updated</td>
      <td>user</td>

    </tr>

  </thead>

  <tbody>
<?php
  if ( $this->data->res) {
    while( $dto = $this->data->res->dto()) { ?>
      <tr data-id="<?php print $dto->id ?>" payment>
        <td><?php print $dto->state ?></td>
        <td><?php printf( '%s<br />%s', $dto->name, $dto->description) ?></td>
        <td class="text-right"><?php print $dto->tax ?></td>
        <td class="text-right"><?php print $dto->value ?></td>
        <td class="text-center"><?php print strings::asShortDate( $dto->updated) ?></td>
        <td><?php print $dto->user_name ?></td>

      </tr>

<?php
    }
  } ?>

  </tbody>

</table>
<script>
$(document).ready( function() {
  $('tr[payment]').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data('id');
    _tr.addClass('pointer').on('click', function( e) {
      window.location.href = _brayworth_.url('payments/view/' + id);

    })

  });

})
</script>
