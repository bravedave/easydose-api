<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
 *
 * // sys::dump( $this->data->invoices);
 *
*/	?>

<style>
i[title="download as CSV"] {margin-top: -22px;}
</style>
<div class="form-row mb-2">
  <div class="col-md-8">
      <input type="search" name="<?= $sid = uniqid( 'ed') ?>"
        id="<?= $sid ?>" placeholder="search..." class="form-control"
        autofocus />

  </div>

  <div class="col-md-4 pt-2" id="<?= $fid = uniqid('ed_') ?>"></div>

</div>

<div class="row">
  <div class="col">
    <table class="table table-sm" id="<?= $tid = uniqid('ed_') ?>">
      <thead class="small">
        <tr>
          <td style="width: 4.5rem;" role="sort-header" data-key="date">date</td>
          <td style="width: 2.5rem;" role="sort-header" data-key="id" data-sorttype="numeric">id</td>
          <td style="width: 5.5rem;" role="sort-header" data-key="state">state</td>
          <td style="width: 4.5rem;" role="sort-header" data-key="state_changed">change</td>
          <td role="sort-header" data-key="user_name">proprietor</td>
          <td role="sort-header" data-key="site">site</td>
          <td class="text-right">value</td>

        </tr>

      </thead>

      <tbody>
        <?php while ( $dto = $this->data->invoices->dto()) { ?>
          <tr invoice
            data-id="<?= $dto->id ?>"
            data-date="<?= $dto->created ?>"
            data-state="<?= $dto->state ?>"
            data-state_changed="<?= $dto->state_changed ?>"
            data-user_name="<?= $dto->user_name ?>"
            data-site="<?= $dto->site ?>"
            >
            <td><?= strings::asShortDate( $dto->created) ?></td>
            <td><?= $dto->id ?></td>
            <td><?= $dto->state ?></td>
            <td><?= strings::asShortDate( $dto->state_changed) ?></td>
            <td><?= $dto->user_name ?></td>
            <td><?= $dto->site ?></td>
            <td class="text-right"><?php
              // if ( true) {
              if ( $dto->discount) {
                printf( '<div>%s</div>', number_format( $dto->rate, 2));
                printf( '<div>-%s</div>', number_format( $dto->discount, 2));
                printf( '<div class="border-top border-2 border-dark">%s</div>', number_format( $dto->rate - $dto->discount, 2));

              }
              else {
                print number_format( $dto->rate, 2);

              }

            ?></td>

          </tr>

        <?php } // while ( $dto = $this->data->invoices->dto()) ?>

      </tbody>

    </table>

  </div>

</div>
<script>
$(document).ready( () => {
  let states = [];
  let chks = [];
  let divFilter = $('#<?= $fid ?>');

  let filterRun = function() {
    let _search = $('#<?= $sid ?>');
    let t = _search.val();

    let filter = [];

    $.each( chks, function( i, el) {
      let _el = $(el);
      if ( _el.prop('checked')) {
        filter.push( _el.data('state'));

      }

    });

    $('tr[invoice]', '#<?= $tid ?>').each( function( i, tr) {
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

  $('tr[invoice]', '#<?= $tid ?>').each( ( i, tr) => {
    let _tr = $(tr);
    let id = _tr.data('id');

    let state = _tr.data('state');
    if ( '' == state) {
      state = 'no state';
    }

    if ( states.indexOf( state) < 0) {
      states.push( state);

    }

    _tr.addClass('pointer').on('click', e => {
      window.location.href = _brayworth_.url('account/invoice/' + id);

    });

  });

  $.each( states, ( i, state) => {
    let uid = '<?= $fid ?>' + i;

    let fc = $('<input type="checkbox" class="form-check-input" checked />')
      .attr('name',uid)
      .attr('id',uid)
      .data('state', state);

    chks.push( fc);

    let fcL = $('<label class="form-check-label"></label>').attr('for',uid).html( state);
    $('<div class="form-check-inline"></div>').append( fc).append( fcL).appendTo(divFilter);

    fc.on('change', filterRun);

  });

  $('#<?= $sid ?>').on( 'keyup', filterRun);

  /*--[ a CSV download icon ]--*/
  let invTable = $('#<?= $tid ?>');
  if ( invTable.length == 1) {
    $('<i class="fa fa-fw fa-table noprint pointer pull-right" title="download as CSV"></i>')
    .on( 'click', e => {
      _ed_.csv.call( invTable, 'inv-list.csv');
    })
    .insertBefore( invTable);

  }

});
</script>
