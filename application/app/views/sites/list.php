<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<div class="row">
  <div class="col p-0">
    <table class="table table-striped table-sm small">
      <thead>
        <tr>
          <td>State</td>
          <td role="sort-header" data-key="site">Site</td>
          <td>Tel.</td>
          <td class="d-none d-lg-table-cell">IP</td>
          <td class="d-none d-lg-table-cell">Workstation</td>
          <td>Product</td>
          <td>Active/<br />Patients</td>
          <td class="d-none d-lg-table-cell">OS</td>
          <td class="d-none d-lg-table-cell">Deploy</td>
          <td class="d-none d-lg-table-cell" role="sort-header" data-key="version">Version</td>
          <td>Act</td>
          <td class="d-none d-md-table-cell" role="sort-header" data-key="expires">Expires</td>
          <td class="d-none d-lg-table-cell">Update</td>

        </tr>

      </thead>

      <tbody>
<?php while ( $dto = $this->data->sites->dto()) { ?>
          <tr
            data-site="<?php print $dto->site ?>"
            data-version="<?php print $dto->version ?>"
            data-expires="<?php print $dto->expires ?>">

            <td><?php print $dto->state ?></td>
            <td><?php print $dto->site ?></td>
            <td><?php print $dto->tel ?></td>
            <td class="d-none d-lg-table-cell"><?php print $dto->ip ?></td>
            <td class="d-none d-lg-table-cell"><?php print $dto->workstation ?></td>
            <td><?php print strings::ShortLicense( $dto->productid); ?></td>
            <td><?php print sprintf( '%s/%s', $dto->patients, $dto->patientsActive) ?></td>
            <td class="d-none d-lg-table-cell"><?php print strings::StringToOS($dto->os) ?></td>
            <td class="d-none d-lg-table-cell"><?php print $dto->deployment ?></td>
            <td class="d-none d-lg-table-cell"><?php print $dto->version ?></td>
            <td><?php print ( $dto->activated ? 'yes' : 'no') ?></td>
            <td class="d-none d-md-table-cell"><?php print date( \config::$DATE_FORMAT, strtotime( $dto->expires )) ?></td>
            <td class="d-none d-lg-table-cell"><?php print date( "d/m h:m", strtotime( $dto->updated)) ?></td>

          </tr>

<?php } // while ( $dto = $this->data->sites->dto()) ?>

      </tbody>

      <tfoot>
        <tr>
          <td colspan="13" class="p-0">
            <div class="container-fluid">
              <div class="row">
                <div class="col">
                  <em><?php printf( 'count: %s', count( $this->data->sites)); ?></em>

                </div>

                <div class="col text-right">
                  <em><?php print date( 'c'); ?></em>

                </div>

              </div>

            </div>

          </td>

        </tr>

      </tfoot>

    </table>

  </div>

</div>
