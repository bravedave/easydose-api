<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

  // sys::dump( $this->data->invoices);

  	?>

<table class="table table-sm table-stripped">
  <colgroup>
    <col />
    <col style="width: 3rem;" />
    <col />
    <col />

  </colgroup>

  <thead>
    <tr>
      <td>date</td>
      <td>id</td>
      <td>state</td>
      <td>user</td>

    </tr>

  </thead>

  <tbody>
<?php while ( $dto = $this->data->invoices->dto()) { ?>
    <tr data-id="<?php print $dto->id ?>" invoice>
      <td><?php print strings::asShortDate( $dto->created) ?></td>
      <td><?php print $dto->id ?></td>
      <td><?php print $dto->state ?></td>
      <td><?php print $dto->user_name ?></td>

    </tr>

<?php } // while ( $dto = $this->data->invoices->dto()) ?>

  </tbody>

</table>
<script>
$(document).ready( function() {
  $('tr[invoice]').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data('id');
    _tr.addClass('pointer').on('click', function( e) {
      window.location.href = _brayworth_.url('account/invoice/' + id);

    })

  });

})
</script>
