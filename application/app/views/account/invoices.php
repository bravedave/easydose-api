<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

  // sys::dump( $this->data->invoices);

  if ( $this->data->invoices) {
  	?>
<div class="row py-1 mt-2">
  <div class="col col-12 col-lg-2">
    <i class="fa fa-fw fa-caret-right pointer pull-right" id="show-user-invoices"></i>
    Invoices

	</div>

  <div class="col col-12 col-lg-10">
    <span id="show-user-invoices-table-ellipses" class="pointer">...</span>
		<table class="table table-striped table-sm d-none" id="show-user-invoices-table">
      <thead>
        <tr>
          <td>date</td>
          <td>id</td>
          <td>state</td>

        </tr>

      </thead>

      <tbody>
        <?php foreach ( $this->data->invoices as $dto) { ?>
          <tr invoice data-id="<?php print $dto->id ?>">
            <td><?php print strings::asShortDate( $dto->created) ?></td>
            <td><?php print $dto->id ?></td>
            <td><?php print $dto->state ?></td>

          </tr>

        <?php } // while ( $dto = $this->data->invoices->dto()) ?>

      </tbody>

    </table>

  </div>

</div>
<script>
$(document).ready( function() {
	$('#show-user-invoices, #show-user-invoices-table-ellipses').on( 'click', function(e) {
		var t = $('#show-user-invoices-table');
		if ( t.hasClass( 'd-none')) {
			t.removeClass( 'd-none');
			$('#show-user-invoices-table-ellipses').addClass( 'd-none');
			$('#show-user-invoices').removeClass('fa-caret-right').addClass('fa-caret-down');

		}
		else {
			t.addClass( 'd-none');
			$('#show-user-invoices-table-ellipses').removeClass( 'd-none');
			$('#show-user-invoices').removeClass('fa-caret-down').addClass('fa-caret-right');

		}

	});

  $('tr[invoice]', '#show-user-invoices-table').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data('id');

    _tr.addClass('pointer').on( 'click', function( e) {
      window.location.href = _brayworth_.url('account/invoice/' + id);

    });

  });

});
</script>
<?php	}	// if ( $this->data->invoices)
