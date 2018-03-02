<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<div class="row pb-1">
  <div class="col col-2">
    id
  </div>

  <div class="col col-10">
    <td><?php print $this->data->dto->id ?></td>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    guid
  </div>

  <div class="col col-10">
    <?php print $this->data->dto->guid ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Account
  </div>

  <div class="col col-10">
    <?php if ( $this->data->account) printf('%s (%s)', $this->data->account->name, $this->data->account->id) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    created
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->created)) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Updated
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->updated)) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Sites
  </div>

  <div class="col col-10">
    <table class="table table-striped">
      <colgroup>
        <col />
        <col />
        <col />
      </colgroup>
      <tbody>
<?php while ($dto = $this->data->sites->dto()) {  ?>
        <tr data-id="<?php print $dto->id ?>" site>
          <td><?php print $dto->site ?></td>
          <td><?php print $dto->state ?></td>
          <td><?php print $dto->patientsActive ?>/<?php print $dto->patients ?></td>

        </tr>

<?php } // while ($dto = $this->data->sites->dto())  ?>

      </tbody>

    </table>

  </div>

</div>

<script>
$(document).ready( function() {
  $('tr[site]').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data( 'id');

    _tr
    .addClass('pointer')
    .on( 'click', function( e) {
      window.location.href = _brayworth_.url('sites/view/'+id);

    })
    .on( 'contextmenu', function( e) {
      if (e.shiftKey)
        return;

      e.stopPropagation(); e.preventDefault();

      var _context = _brayworth_.context();
      _context.append($('<a><i class="fa fa-link"></i>view</a>').attr('href',_brayworth_.url('sites/view/'+id)))
      _context.append($('<a href="#"><i class="fa fa-trash"></i>delete</a>').on('click', function(e) {
        var href = $(this).attr('href');
        e.stopPropagation(); e.preventDefault();

        _brayworth_.modal({
          title: 'confirm',
          text: 'Are you sure ?',
          buttons : {
            yes : function( e) {
              hourglass.on();
              window.location.href = _brayworth_.url( 'sites/remove/' + id + '/<?php print $this->data->dto->id ?>');

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
