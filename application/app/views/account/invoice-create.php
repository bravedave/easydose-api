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

	*/	?>
<form class="form" method="POST" action="<?php url::write('invoices') ?>">
  <input type="hidden" name="user_id" value="<?php print $this->data->account->id ?>" />
  <input type="hidden" name="personal" value="<?php print $this->data->personal ?>" />
  <table class="table borderless">
    <tbody>
      <tr>
        <table class="table borderless">
          <colgroup>
            <col span="2" style="width: 50%;" />

          </colgroup>

          <tbody>
            <tr>
              <td>
                &nbsp;

              </td>

              <td>
                <div>
                  <?php print $this->data->sys->name ?>

                </div>

                <div>
                  <?php print $this->data->sys->street ?>

                </div>

                <div>
                  <?php print $this->data->sys->town ?>

                </div>

                <div>
                  <?php printf( '%s %s', $this->data->sys->state, $this->data->sys->postcode) ?>

                </div>

                <div>
                  <?php printf( 'BSB : %s Account : %s', $this->data->sys->bank_bsb, $this->data->sys->bank_account) ?>

                </div>

              </td>

            </tr>
            <tr>
              <td>
                <div>
                  <?php print $this->data->account->name ?>

                </div>

                <div>
                  <?php print $this->data->account->email ?>

                </div>

              </td>

              <td>
                &nbsp;

              </td>

            </tr>

            <tr>
              <td>
                Invoice Number: #

              </td>

              <td class="text-right">
                Invoice Date: <?php print date( \config::$DATE_FORMAT ) ?>


              </td>

            </tr>

          </tbody>

        </table>

      </tr>

      <tr>
        <td>

          <table class="table table-striped">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td>Description</td>
                <td class="text-right">Rate</td>
                <td class="text-right">Term</td>

              </tr>

            </thead>

            <tbody>

              <?php
              foreach ( $this->data->products as $dto) {
                // sys::dump( $product);  ?>
                <tr>
                  <td><input type="radio" name="product_id" required value="<?php print $dto->id ?>" /></td>
                  <td><?php printf( '%s<br />%s', $dto->name, $dto->description); ?></td>
                  <td class="text-right" rate><?php print number_format( $dto->rate, 2) ?></td>
                  <td class="text-right"><?php print $dto->term ?></td>

                </tr>
                <?php
              }	// foreach ( $this->data->products as product)	?>

              <?php
              foreach ( $this->data->productsWKS as $dto) {
                // sys::dump( $product);  ?>
                <tr>
                  <td><input type="radio" name="workstation_id" value="<?php print $dto->id ?>" /></td>
                  <td><?php printf( '%s<br />%s', $dto->name, $dto->description); ?></td>
                  <td class="text-right" rate><?php print number_format( $dto->rate, 2) ?></td>
                  <td class="text-right"><?php print $dto->term ?></td>

                </tr>
                <?php
              }	// foreach ( $this->data->products as product)	?>

              <tr>
                <td colspan="2" style="border-top: 6px double #dee2e6;">
                  <strong>
                    Total:

                  </strong>

                </td>
                <td class="text-right" style="border-top: 6px double #dee2e6;">
                  <strong id="invoice-total-box">&nbsp;</strong>

                </td>

                <td style="border-top: 6px double #dee2e6;">&nbsp;</td>

              </tr>

              <tr>
                <td colspan="2">
                  Total includes GST:
                </td>
                <td class="text-right" id="invoice-gst-box">&nbsp;</td>
                <td>&nbsp;</td>

              </tr>

              <tr>
                <td colspan="4" class="text-right">
                  <input type="submit" name="action" class="btn btn-primary" value="create invoice" />

                </td>

              </tr>

            </tbody>

          </table>

          <?php
          // sys::dump( $this->data) ?>

        </td>

      </tr>

      <tr>
        <td>
          <table class="table borderless">
            <tbody>
              <tr>
                <td>
                  <small>
                    <div>
                      <strong>year</strong>

                    </div>

                    <p>
                      Products with a term of <em>year</em> are valid 1 year from the payment date.
                      Where the product is an extension, the product will be valid 1 year from the
                      expiry date of the product
                    </p>

                  </small>

                </td>

              </tr>

            </tbody>

          </table>

        </td>

      </tr>

    </tbody>

  </table>

</form>
<script>
$(document).ready( function() {
  $('input[type="radio"][name="product_id"], input[type="radio"][name="workstation_id"]').on( 'change', function(){
    var product = $('input[type="radio"][name="product_id"]:checked');
    var wks = $('input[type="radio"][name="workstation_id"]:checked');

    var rate = function( el) {
      var tr = el.closest( 'tr');
      // console.log( tr.text());
      return Number( $('td[rate]', tr).html())

    }

    var iProduct = Number( rate( product));
    if ( isNaN( iProduct))
      iProduct = 0;

    var iWks = 0;
    if ( wks.length > 0) {
      // console.log( 'calcing', wks);
      iWks = Number( rate( wks));
      if ( isNaN( iWks))
        iWks = 0;

    }

    var iTot = iWks + iProduct;

    $('#invoice-total-box').html( iTot.formatCurrency());
    $('#invoice-gst-box').html( (iTot/11).formatCurrency());

    // console.log( 'calced', iProduct, iWks);


  });

})
</script>
