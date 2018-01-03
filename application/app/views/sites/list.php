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
    <col span="8" />
    <col style="width: 70px;" />
    <col style="width: 90px;" />
    <col style="width: 10px;" />
  </colgroup>

  <thead>
    <tr>
      <td>State</td>
      <td>Site</td>
      <td>Tel.</td>
      <td>IP</td>
      <td>Workstation</td>
      <td>Product ID</td>
      <td>Active/<br />Patients</td>
      <td>OS</td>
      <td>Deploy</td>
      <td>Version</td>
      <td>Active</td>
      <td>Expires</td>
      <td>Updated</td>
      <td>&nbsp;</td>
    </tr>
  </thead>

  <tbody>
    <?php while ( $dto = $this->data->sites->dto()) { ?>
      <tr>
        <td><?= $dto->state ?></td>
        <td><?= $dto->site ?></td>
        <td><?= $dto->tel ?></td>
        <td><?= $dto->ip ?></td>
        <td><?= $dto->workstation ?></td>
        <td><?= $dto->productid ?></td>
        <td><?= sprintf( '%s/%s', $dto->patients, $dto->patientsActive) ?></td>
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

        $dto->os ?></td>
        <td><?= $dto->deployment ?></td>
        <td><?= $dto->version ?></td>
        <td><?= $dto->activated ? 'yes' : 'no' ?></td>
        <td><?= date( "d/m/y", strtotime( $dto->expires )) ?></td>
        <td><?= date( "d/m h:m", strtotime( $dto->updated)) ?></td>

      </tr>

    <?php } // while ( $dto = $this->data->sites->dto()) ?>

  </tbody>

</table>

<?php
    // sys::dump( $this->data->sites, NULL, FALSE);
