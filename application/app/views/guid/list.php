<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<div class="row">
  <div class="col p-0">
    <table class="table table-striped">
      <thead>
        <tr>
          <td class="d-none d-lg-table-cell" style="width: 40px;">id</td>
          <td>guid</td>
          <td class="d-none d-lg-table-cell" style="width: 18em;">site</td>
          <td class="d-none d-lg-table-cell" style="width: 14em;">name</td>
          <td class="text-center" style="width: 8em;">created</td>
          <td class="d-none d-lg-table-cell text-center" style="width: 8em;">updated</td>

        </tr>

      </thead>

      <tbody>
        <?php while ( $dto = $this->data->res->dto()) {  ?>
          <tr
            data-id="<?php print $dto->id ?>" row-guid>
            <td class="d-none d-lg-table-cell"><?php print $dto->id ?></td>
            <td><?php print $dto->guid ?>
              <div class="d-block d-lg-none">
                <?php printf('%s<br />%s', $dto->site, $dto->name); ?>

              </div>

            </td>
            <td class="d-none d-lg-table-cell"><?php print $dto->site ?></td>
            <td class="d-none d-lg-table-cell"><?php print $dto->name ?></td>
            <td class="text-center"><?php print date( \config::$DATE_FORMAT, strtotime( $dto->created)) ?></td>
            <td class="d-none d-lg-table-cell text-center"><?php print date( \config::$DATE_FORMAT, strtotime( $dto->updated)) ?></td>

          </tr>

        <?php } ?>

      </tbody>

    </table>

  </div>

</div>
<?php
  // sys::dump( $this->data->res, NULL, FALSE); ?>

<script>
$(document).ready( function() {
  $('tr[row-guid]').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data('id');

    _tr
    .addClass('pointer')
    .on( 'click', function( e) {
      window.location.href = _brayworth_.url('guid/view/'+id);

    })
    .on( 'contextmenu', function( e) {
      if (e.shiftKey)
        return;

      e.stopPropagation(); e.preventDefault();

      var _context = _brayworth_.context();
      _context.append($('<a><i class="fa fa-link"></i>view</a>').attr('href',_brayworth_.url('guid/view/'+id)))
      _context.append($('<a href="#"><i class="fa fa-trash"></i>delete</a>').on('click', function(e) {
        e.stopPropagation(); e.preventDefault();

        _brayworth_.modal({
          title: 'confirm',
          text: 'Are you sure ?',
          buttons : {
            yes : function( e) {
              hourglass.on();
              window.location.href = _brayworth_.url( 'guid/remove/' + id);

            }

          }

        });

        _context.close();

      }));

      _context.open( e);

    })

  });

})
</script>
