<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<div class="row pb-1">
  <div class="col col-2">
    id
  </div>

  <div class="col col-10">
    <td><?php print $this->data->dto->id ?></td>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    guid
  </div>

  <div class="col col-10">
    <?php print $this->data->dto->guid ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Account
  </div>

  <div class="col col-10">
    <?php if ( $this->data->account) printf('%s (%s)', $this->data->account->name, $this->data->account->id) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    created
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->created)) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Updated
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->updated)) ?>

  </div>

</div>

<div class="row pb-1">
  <div class="col col-2">
    Sites
  </div>

  <div class="col col-10">
    <table class="table table-striped">
      <tbody>
<?php while ($dto = $this->data->sites->dto()) {  ?>
        <tr>
          <td><?php print $dto->site ?></td>
          <td><?php print $dto->state ?></td>
          <td><?php print $dto->patientsActive ?>/<?php print $dto->patients ?></td>

        </tr>
<?php } // while ($dto = $this->data->sites->dto())  ?>
      </tbody>

    </table>

  </div>

</div>

<script>
$(document).ready( function() {})
</script>
