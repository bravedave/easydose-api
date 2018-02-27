<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<table class="table table-striped table-sm small">
  <colgroup>
    <col style="width: 70px;" />
    <col />
    <col style="width: 90px;" />
    <col span="4" />
    <col style="width: 100px;" />
    <col />
    <col />
    <col />
    <col style="width: 80px;" />
    <col style="width: 90px;" />
  </colgroup>

  <thead>
    <tr>
      <td>State</td>
      <td role="sort-header" data-key="site">Site</td>
      <td>Tel.</td>
      <td>IP</td>
      <td>Workstation</td>
      <td>Product ID</td>
      <td>Active/<br />Patients</td>
      <td>OS</td>
      <td>Deploy</td>
      <td role="sort-header" data-key="version">Version</td>
      <td>Active</td>
      <td role="sort-header" data-key="expires">Expires</td>
      <td>Updated</td>

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
        <td><?php print $dto->ip ?></td>
        <td><?php print $dto->workstation ?></td>
        <td><?php print $dto->productid ?></td>
        <td><?php print sprintf( '%s/%s', $dto->patients, $dto->patientsActive) ?></td>

        <td><?php
    if ( preg_match( '@^Microsoft Windows XP@', $dto->os ))
      print 'WinXP';

    elseif ( preg_match( '@^Microsoft Windows \[Version 6.1.7601\]@', $dto->os ))
      print 'Win7/2008 SP1';

    elseif ( preg_match( '@^Microsoft Windows \[Version 6.2.9200\]@', $dto->os ))
      print 'Win8/2012';

    elseif ( preg_match( '@^Microsoft Windows \[Version 6.3.9200\]@', $dto->os ))
      print 'Win8.1/2012 R2';

    elseif ( preg_match( '@^Microsoft Windows \[Version 6.3.9600\]@', $dto->os ))
      print 'Win8.1 U1/2012';

    elseif ( preg_match( '@^Microsoft Windows \[Version 10@', $dto->os ))
      print 'Win10';

    else
      print $dto->os;

        ?></td>

        <td><?php print $dto->deployment ?></td>
        <td><?php print $dto->version ?></td>
        <td><?php print ( $dto->activated ? 'yes' : 'no') ?></td>
        <td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->expires )) ?></td>
        <td><?php print date( "d/m h:m", strtotime( $dto->updated)) ?></td>

      </tr>

<?php } // while ( $dto = $this->data->sites->dto()) ?>

  </tbody>

</table>
