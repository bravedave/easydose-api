<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<table class="table table-striped">
  <colgroup>
    <col style="width: 40px;" />
    <col />
    <col />
    <col span="2" style="width: 150px;" />
    <col style="width: 2em;" />
    <col style="width: 2em;" />

  </colgroup>

  <thead>
    <tr>
      <td>id</td>
      <td>guid</td>
      <td>name</td>
      <td>created</td>
      <td>updated</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>

    </tr>

  </thead>

  <tbody>
<?php while ( $dto = $this->data->res->dto()) {  ?>
    <tr row-guid data-guid="<?php print $dto->guid ?>">
      <td><?php print $dto->id ?></td>
      <td><?php print $dto->guid ?></td>
      <td><?php print $dto->name ?></td>
      <td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->created)) ?></td>
      <td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->updated)) ?></td>
      <td>
        <a href="<?php url::write( sprintf( 'guid/view/%s', $dto->id)) ?>"><i class="fa fa-eye" title="view"></i></a>

      </td>
      <td>
        <a href="<?php url::write( sprintf( 'guid/remove/%s', $dto->id)) ?>" are-you-sure><i class="fa fa-times text-danger" title="delete"></i></a>

      </td>

    </tr>

<?php } ?>

  </tbody>

</table>
<?php
  // sys::dump( $this->data->res, NULL, FALSE); ?>

<script>
$(document).ready( function() {
  $('tr[row-guid]').each( function( i, tr) {
    var _tr = $(tr);
    var guid = _tr.data('guid');

  });

  $('a[are-you-sure]').on( 'click', function(e) {
    var href = $(this).attr('href');
    e.stopPropagation(); e.preventDefault();

    _brayworth_.modal({
      title: 'confirm',
      text: 'Are you sure ?',
      buttons : {
        yes : function( e) {
          hourglass.on();
          window.location.href = href;

        }

      }

    });

  });

})
</script>
