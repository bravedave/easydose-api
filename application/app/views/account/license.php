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

	*/ ?>
<div class="row py-1 mt-4">
  <div class="col col-12 col-lg-2">
    <strong>License</strong>

  </div>

  <div class="col col-12 col-lg-10">
    <table class="table table-striped table-sm">
      <colgroup>
					<col />
					<col />
					<col style="width: 7em"/>
					<col style="width: 7em"/>
			</colgroup>

      <thead>
        <tr>
          <td>License</td>
          <td>Wks</td>
          <td class="text-center">Expires</td>
          <td class="text-center">State</td>

        </tr>

      </thead>

      <tbody>
<?php
  if ( $ag = $this->data->license) {

    // sys::dump( $ag);  ?>
        <tr>
          <td><?php printf( '%s : %s', $ag->product, $ag->description) ?></td>
          <td class="text-center"><?php print $ag->workstations ?></td>
          <td class="text-center"><?php print strings::asShortDate( $ag->expires) ?></td>
          <td class="text-center"><?php print $ag->state ?></td>

        </tr>

<?php
  } ?>

      </tbody>

    </table>

  </div>

</div>
