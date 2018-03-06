<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

  // sys::dump( $this->data->invoices);

  	?>

<table class="table table-sm table-stripped">
  <thead>
    <tr>
      <td>date</td>
      <td>id</td>
      <td>state</td>
      <td>user</td>

    </tr>

  </thead>

  <tbody>
<?php while ( $dto = $this->data->invoices->dto()) { ?>
    <tr>
      <td><?php print strings::asShortDate( $dto->created) ?></td>
      <td><?php print $dto->id ?></td>
      <td><?php print $dto->state ?></td>
      <td><?php print $dto->user_name ?></td>

    </tr>

<?php } // while ( $dto = $this->data->invoices->dto()) ?>

  </tbody>

</table>
