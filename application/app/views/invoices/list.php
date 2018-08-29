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
<div class="row">
  <div class="col" id="<?php print $fid = uniqid('ed_') ?>"></div>

</div>

<div class="row">
  <table class="table table-sm" id="<?php print $tid = uniqid('ed_') ?>">
    <thead>
      <tr>
        <td style="width: 4.5rem;">date</td>
        <td style="width: 2.5rem;">id</td>
        <td style="width: 5.5rem;">state</td>
        <td style="width: 4.5rem;">changed</td>
        <td>user</td>
        <td>site</td>

      </tr>

    </thead>

    <tbody>
      <?php while ( $dto = $this->data->invoices->dto()) { ?>
        <tr invoice
          data-id="<?php print $dto->id ?>"
          data-state="<?php print $dto->state ?>"
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
<script>
$(document).ready( function() {
  let states = [];
  let chks = [];
  let divFilter = $('#<?php print $fid ?>')
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

    let fcL = $('<label class="form-check-label" />').attr('for',uid).html( state);
    $('<div class="form-check-inline" />').append( fc).append( fcL).appendTo(divFilter);

    fc.on('change', function() {
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
          _tr.removeClass('d-none');

        }

      });

    });

  });

});
</script>
