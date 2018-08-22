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
          <td class="d-none d-lg-table-cell" style="width: 18em;">name</td>
          <td class="text-center" style="width: 5em;">use license</td>
          <td class="text-center" style="width: 8em;">created</td>
          <td class="d-none d-lg-table-cell text-center" style="width: 8em;">updated</td>

        </tr>

      </thead>

      <tbody>
        <?php while ( $dto = $this->data->res->dto()) {  ?>
          <tr
            data-id="<?php print $dto->id ?>"
            data-license="<?php print (int)$dto->use_license ?>"
            row-guid>
            <td class="d-none d-lg-table-cell"><?php print $dto->id ?></td>
            <td><?php print $dto->guid ?>
              <div class="text-muted small">
                <?php printf('%s', $dto->site); ?>

              </div>

            </td>
            <td class="d-none d-lg-table-cell"><?php print $dto->name ?></td>
            <td class="text-center" style="width: 5em;" license-indicator><?php
              if ( $dto->use_license ) {
                print strings::html_tick;
              } ?></td>
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

      _brayworth_.hideContexts();
      let _context = _brayworth_.context();
      _context.append( $('<a><i class="fa fa-link"></i>view</a>').attr('href',_brayworth_.url('guid/view/'+id)));
      _context.append( ( function() {
        let a = $('<a href="#">use this license</a>').on('click', function(e) {
          e.stopPropagation(); e.preventDefault();

          let newVal = ( _tr.data('license') == 1 ? 0 : 1)

          _brayworth_.post({
            url : _brayworth_.url('guid'),
            data : {
              action : 'use-version-2-license',
              value : newVal,
              id : id,

            }

          })
          .then( function(d) {
            _brayworth_.growl(d);
            _tr.data('license', newVal)
            $('td[license-indicator]', _tr).html(newVal == 1 ? '<?php print strings::html_tick ?>' : '');

          });

          _context.close();

        });

        if ( _tr.data('license') == 1) {
            a.prepend('<i class="fa fa-check"></i>');

        }

        return (a);

      })());

<?php if ( \currentUser::isProgrammer()) { ?>
      _context.append('<hr />');
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

<?php } // if ( \currentUser::isProgrammer()) ?>

      _context.open( e);

    })

  });

})
</script>
