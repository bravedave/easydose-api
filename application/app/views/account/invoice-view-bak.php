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
<form class="form" method="POST" action="<?php url::write('account') ?>">
  <input type="hidden" name="id" value="<?php print $this->data->invoice->id ?>" />
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
                  <?php print $this->data->account->name ?>

                </div>

                <div>
            			<?php print $this->data->account->business_name ?>

            		</div>

            		<div>
            			<?php print $this->data->account->street ?>

            		</div>

            		<div>
            			<?php printf( '%s, %s %s', $this->data->account->town,
                    $this->data->account->state, $this->data->account->postcode ); ?>

            		</div>

            		<div>
                  <?php printf( 'ABN: %s', $this->data->account->abn) ?>

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
                Invoice Number: # <?php print $this->data->invoice->id ?>

              </td>

              <td class="text-right">
                Invoice Date: <?php print date( \config::$DATE_FORMAT, strtotime($this->data->invoice->created)) ?>


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
                <td>Description</td>
                <td class="text-right">Rate</td>
                <td class="text-right">Term</td>

              </tr>

            </thead>

            <tbody>

              <?php
              foreach ( $this->data->invoice->lines as $dto) {
                // sys::dump( $product);  ?>
                <tr>
                  <td><?php printf( '%s<br />%s', $dto->name, $dto->description); ?></td>
                  <td class="text-right" rate><?php print number_format( $dto->rate, 2) ?></td>
                  <td class="text-right"><?php print $dto->term ?></td>

                </tr>
                <?php
              }	// foreach ( $this->data->products as product)	?>

              <tr>
                <td style="border-top: 6px double #dee2e6;">
                  <strong>
                    Total:

                  </strong>

                </td>
                <td class="text-right" style="border-top: 6px double #dee2e6;">
                  <strong id="invoice-total-box"><?php print number_format( $this->data->invoice->total, 2) ?></strong>

                </td>

                <td style="border-top: 6px double #dee2e6;">&nbsp;</td>

              </tr>

              <tr>
                <td>
                  Total includes GST:
                </td>
                <td class="text-right" id="invoice-gst-box"><?php print number_format( $this->data->invoice->tax, 2) ?></td>
                <td>&nbsp;</td>

              </tr>

              <tr>
                <td colspan="3" class="text-right">
                  <input type="submit" name="action" class="btn btn-primary" value="pay invoice" />

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
