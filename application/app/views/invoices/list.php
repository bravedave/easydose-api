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
<style>
i[title="download as CSV"] {margin-top: -18px;}
</style>
<div class="form-row">
  <div class="col-md-8">
      <input type="search" name="<?php print $sid = uniqid( 'ed') ?>"
        id="<?php print $sid ?>" placeholder="search..." class="form-control"
        autofocus />

  </div>

  <div class="col-md-4 pt-2" id="<?php print $fid = uniqid('ed_') ?>"></div>

</div>

<div class="row">
  <div class="col">
    <table class="table table-sm" id="<?php print $tid = uniqid('ed_') ?>">
      <thead>
        <tr>
          <td style="width: 4.5rem;" role="sort-header" data-key="date">date</td>
          <td style="width: 2.5rem;" role="sort-header" data-key="id" data-sorttype="numeric">id</td>
          <td style="width: 5.5rem;" role="sort-header" data-key="state">state</td>
          <td style="width: 4.5rem;" role="sort-header" data-key="state_changed">change</td>
          <td role="sort-header" data-key="user_name">proprietor</td>
          <td role="sort-header" data-key="site">site</td>

        </tr>

      </thead>

      <tbody>
        <?php while ( $dto = $this->data->invoices->dto()) { ?>
          <tr invoice
            data-id="<?php print $dto->id ?>"
            data-date="<?php print $dto->created ?>"
            data-state="<?php print $dto->state ?>"
            data-state_changed="<?php print $dto->state_changed ?>"
            data-user_name="<?php print $dto->user_name ?>"
            data-site="<?php print $dto->site ?>"
            >
            <td><?php print strings::asShortDate( $dto->created) ?></td>
            <td><?php print $dto->id ?></td>
            <td><?php print $dto->state ?></td>
            <td><?php print strings::asShortDate( $dto->state_changed) ?></td>
            <td><?php print $dto->user_name ?></td>
            <td><?php print $dto->site ?></td>

          </tr>

        <?php } // while ( $dto = $this->data->invoices->dto()) ?>

      </tbody>

    </table>

  </div>

</div>
<script>
$(document).ready( function() {
  let states = [];
  let chks = [];
  let divFilter = $('#<?php print $fid ?>');

  let filterRun = function() {
    let _search = $('#<?php print $sid ?>');
    let t = _search.val();

    let filter = [];

    $.each( chks, function( i, el) {
      let _el = $(el);
      if ( _el.prop('checked')) {
        filter.push( _el.data('state'));

      }

    });

    $('tr[invoice]', '#<?php print $tid ?>').each( function( i, tr) {
      let _tr = $(tr);
      let state = _tr.data('state');
      if ( '' == state) {
        state = 'no state';
      }

      if ( filter.indexOf( state) < 0) {
        _tr.addClass('d-none');

      }
      else {
        if ( t == '') {
          _tr.removeClass('d-none');

        }
        else {
          var rex = new RegExp(t,'i')
          // console.log( t, _tr.text())
          if ( rex.test( _tr.text())) {
            _tr.removeClass('d-none');
          }
          else {
            _tr.addClass('d-none');

          }

        }

      }

    });

  };

  $('tr[invoice]', '#<?php print $tid ?>').each( function( i, tr) {
    let _tr = $(tr);
    let id = _tr.data('id');

    let state = _tr.data('state');
    if ( '' == state) {
      state = 'no state';
    }

    if ( states.indexOf( state) < 0) {
      states.push( state);

    }

    _tr.addClass('pointer').on('click', function( e) {
      window.location.href = _brayworth_.url('account/invoice/' + id);

    })

  });

  $.each( states, function( i, state) {
    let uid = '<?php print $fid ?>' + i;

    let fc = $('<input type="checkbox" class="form-check-input" checked />')
      .attr('name',uid)
      .attr('id',uid)
      .data('state', state);

    chks.push( fc);

    let fcL = $('<label class="form-check-label"></label>').attr('for',uid).html( state);
    $('<div class="form-check-inline"></div>').append( fc).append( fcL).appendTo(divFilter);

    fc.on('change', filterRun);

  });

  $('#<?php print $sid ?>').on( 'keyup', filterRun);

  /*--[ a CSV download icon ]--*/
  let invTable = $('#<?php print $tid ?>');
  if ( invTable.length == 1) {
    $('<i class="fa fa-fw fa-table noprint pointer pull-right" title="download as CSV"></i>')
    .on( 'click', function( e) {
      _ed_.csv.call( invTable, 'inv-list.csv');
    })
    .insertBefore( invTable);

  }

});
</script>
