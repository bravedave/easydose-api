<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
  $dto = $this->data->site;
  ?>
<div class="row py-1">
  <div class="col col-3 pt-1 small">
    State

  </div>

  <div class="col col-9">
    <?php print $dto->state ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Site

  </div>

  <div class="col col-9">
    <?php print $dto->site ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Tel.

  </div>

  <div class="col col-9">
    <?php print $dto->tel ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    IP

  </div>

  <div class="col col-9">
    <?php print $dto->ip ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Workstation

  </div>

  <div class="col col-9">
    <?php print $dto->workstation ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Product

  </div>

  <div class="col col-9">
    <?php print strings::ShortLicense( $dto->productid); ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Active/<br />Patients

  </div>

  <div class="col col-9">
    <?php print sprintf( '%s/%s', $dto->patients, $dto->patientsActive) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    OS

  </div>

  <div class="col col-9">
    <?php print strings::StringToOS($dto->os) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Deploy

  </div>

  <div class="col col-9">
    <?php print $dto->deployment ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Version

  </div>

  <div class="col col-9">
    <?php print $dto->version ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Act

  </div>

  <div class="col col-9">
    <?php print ( $dto->activated ? 'yes' : 'no') ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Expires

  </div>

  <div class="col col-9">
    <?php print date( \config::$DATE_FORMAT, strtotime( $dto->expires )) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Update

  </div>

  <div class="col col-9">
    <?php print date( "d/m h:m", strtotime( $dto->updated)) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col pt-1 small">
    <em><?php print date( 'c'); ?></em>

  </div>

</div>
