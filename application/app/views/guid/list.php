<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<table class="table table-striped">
  <thead>
    <tr>
      <td>id</td>
      <td>guid</td>
      <td>name</td>
      <td>created</td>
      <td>updated</td>

    </tr>

  </thead>

  <tbody>
<?php while ( $dto = $this->data->res->dto()) {  ?>
    <tr>
      <td><?php print $dto->id ?></td>
      <td><?php print $dto->guid ?></td>
      <td><?php print $dto->name ?></td>
      <td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->created)) ?></td>
      <td><?php print date( \config::$DATE_FORMAT, strtotime( $dto->updated)) ?></td>

    </tr>

<?php } ?>

  </tbody>

</table>

<?php
  // sys::dump( $this->data->res, NULL, FALSE);
