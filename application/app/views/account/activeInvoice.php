<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		viewer class for user table

	security:
	 	Ordinary Authenticated user - non admin

	*/
  $inv = $this->data->license->license;
  ?>
<div class="row py-1 mt-2">
  <div class="col-12 col-lg-2">
		<i class="fa fa-fw fa-caret-right pointer pull-right" id="show-activeInvoices"></i>
    Active Invoice
  </div>
  <div class="col-12 col-lg-10">
    <span id="show-activeInvoices-table-ellipses" class="pointer">...</span>
    <table class="table table-striped table-sm d-none" id="show-activeInvoices-table">

      <tbody>
        <tr>
          <td class="p-0">
            <table class="table table-striped table-sm m-0">
              <colgroup>
                  <col span="5" style="width: 20%;"/>

              </colgroup>

              <thead>
                <tr>
                  <td>#</td>
                  <td class="text-center">state</td>
                  <td class="text-center">created</td>
                  <td class="text-center">expires</td>
                  <td class="text-right">effective</td>

                </tr>

              </thead>

              <tbody>
                <tr>
                  <td><?php print $inv->id ?></td>
                  <td class="text-center"><?php print $inv->state ?></td>
                  <td class="text-center"><?php print strings::asLocalDate( $inv->created) ?></td>
                  <td class="text-center"><?php print strings::asLocalDate( $inv->expires) ?></td>
                  <td class="text-right"><?php print strings::asLocalDate( $inv->effective) ?></td>

                </tr>

              </tbody>

            </table>

          </td>

        </tr>

        <tr>
          <td class="p-0">
            <table class="table table-striped table-sm m-0">
              <tbody>
                <?php foreach ( $inv->lines as $line) { ?>
                  <tr>
                    <td><?php printf( '%s<br />%s', $line->description, $line->name) ?></td>
                    <td class="text-right"><?php print $line->rate ?></td>
                    <td><?php print $line->term ?></td>

                  </tr>

                <?php } ?>

                <tr>
                  <td>Total</td>
                  <td class="text-right"><?php print $inv->total ?></td>
                  <td>&nbsp;</td>

                </tr>

                <tr>
                  <td>total includes GST</td>
                  <td class="text-right"><?php print $inv->tax ?></td>
                  <td>&nbsp;</td>

                </tr>

              </tbody>

            </table>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</div>
<script>
$(document).ready( function() {
  $('#show-activeInvoices, #show-activeInvoices-table-ellipses').on( 'click', function(e) {
    var t = $('#show-activeInvoices-table');
    if ( t.hasClass( 'd-none')) {
      t.removeClass( 'd-none');
      $('#show-activeInvoices-table-ellipses').addClass( 'd-none');
      $('#show-activeInvoices').removeClass('fa-caret-right').addClass('fa-caret-down');

    }
    else {
      t.addClass( 'd-none');
      $('#show-activeInvoices-table-ellipses').removeClass( 'd-none');
      $('#show-activeInvoices').removeClass('fa-caret-down').addClass('fa-caret-right');

    }

  })

})
</script>
<?php
  // \sys::dump($this->data->license->license, NULL, FALSE);
  ?>
