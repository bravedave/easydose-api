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
    Email

  </div>

  <div class="col col-9">
    <?php print $dto->email ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    ABN

  </div>

  <div class="col col-9">
    <?php print $dto->abn ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Workstation / IP

  </div>

  <div class="col col-9">
    <?php print $dto->workstation ?>
    /
    <?php print $dto->ip ?>

  </div>

</div>


<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Product

  </div>

  <div class="col col-9">
    <?php print strings::ShortLicense( $dto->productid); ?>
    <?php printf( ' (%s)', strings::ShortLicense( $dto->productid_report)); ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Active/Patients

  </div>

  <div class="col col-9">
    <?php print sprintf( '%s/%s', $dto->patientsActive, $dto->patients) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    OS / Deploy / Version

  </div>

  <div class="col col-9">
    <?php print strings::StringToOS($dto->os) ?>
    /
    <?php print $dto->deployment ?>
    /
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
    <?php printf( ' (%s)', date( \config::$DATE_FORMAT, strtotime( $dto->expires_report ))) ?>

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

<?php if ( $this->data->guid) { ?>
<div class="row py-1">
  <div class="col col-3 pt-1 small">
    GUID

  </div>

  <div class="col col-9"><?php

  printf( '<a href="%s">%s</a>', url::tostring('guid/view/' . $this->data->guid->id), $dto->guid )

  ?></div>

</div>

<?php } // if ( $this->data->guid) ?>

<div class="row py-1">
  <div class="col col-3 pt-1 small">
    Account

  </div>

  <div class="col col-9"><?php

  if ( $this->data->account) {
    // sys::dump( $this->data->account, NULL, FALSE);
    if ($this->data->account->business_name == $this->data->account->name) {
      printf( '<a href="%s">%s</a>',
        url::tostring('users/view/' . $this->data->account->id),
        $this->data->account->name );

    }
    else {
      printf( '<a href="%s">%s (%s)</a>',
        url::tostring('users/view/' . $this->data->account->id),
        $this->data->account->business_name,
        $this->data->account->name );

    }

  }
  else {
    printf( '<a href="%s">no account - create</a>',
      url::tostring('sites/createaccount/' . $this->data->site->id));

  } // if ( $this->data->account)

  ?></div>

</div>

<div class="row py-1">
  <div class="col" data-provide="easyLog-table" data-site="<?php print $this->data->site->id ?>"></div>

</div>

<div class="row py-1">
  <div class="col pt-1 small">
    <em><?php print date( 'c'); ?></em>

  </div>

</div>
