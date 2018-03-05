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

              </td>

            </tr>
            <tr>
              <td>
                <div>
                  <?php print currentUser::name() ?>

                </div>

                <div>
                  <?php print currentUser::email() ?>

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

          <table class="table">
            <thead>
              <tr>
                <td>Product</td>
                <td>Description</td>
                <td class="text-right">Rate</td>
                <td class="text-right">Term</td>

              </tr>

            </thead>
<?php
  $iTot = 0;
  ?>
            <tbody>

              <tr>
                <td><?php print $this->data->product->name ?></td>
                <td><?php print $this->data->product->description ?></td>
                <td class="text-right"><?php print number_format( (float)$this->data->product->rate, 2 ) ?></td>
                <td class="text-right"><?php print $this->data->product->term ?></td>

              </tr>

              <?php
                $iTot += (float)$this->data->product->rate;
                ?>

                <tr>
                  <td colspan="2" style="border-top: 6px double #dee2e6;">
                    <strong>
                      Total:

                    </strong>

                  </td>
                  <td class="text-right" style="border-top: 6px double #dee2e6;">
                    <strong>
                      <?php print number_format( (float)$iTot, 2 ) ?>

                    </strong>

                  </td>

                  <td style="border-top: 6px double #dee2e6;">&nbsp;</td>

                </tr>

                <tr>
                  <td colspan="2">
                    Total includes GST:
                  </td>
                  <td class="text-right"><?php print number_format( (float)$this->data->product->rate / 11, 2 ) ?></td>
                  <td>&nbsp;</td>

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
