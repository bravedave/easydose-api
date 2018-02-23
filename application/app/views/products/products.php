<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-striped table-sm">
  <thead>
    <tr>
      <td>id</td>
      <td>name</td>
      <td>description</td>
      <td>rate</td>
      <td>term</td>
      <td>created</td>
      <td>updated</td>

    </tr>


  </thead>

  <tbody>
<?php
  if ( $this->data->res) {
    while ( $dto = $this->data->res->dto()) { ?>
    <tr product>
      <td id><?php print $dto->id ?></td>
      <td><?php print $dto->name ?></td>
      <td><?php print $dto->description ?></td>
      <td><?php print $dto->rate ?></td>
      <td><?php print $dto->term ?></td>
      <td><?php print strings::asShortDate( $dto->created) ?></td>
      <td><?php print strings::asShortDate( $dto->updated) ?></td>

    </tr>

<?php
    } // foreach( $this->data->dtoSet as $dto)
  } ?>

  </tbody>

</table>
<div class="row py-1">
  <div class="col">
    [<a href="<?php url::write('products/edit') ?>">new product</a>]

  </div>

</div>
<script>
$(document).ready( function() {
  $('tr[product]').addClass('pointer').on('click', function( e) {
    var id = $('td[id]', this).text();
    window.location.href = _brayworth_.url('products/edit/' + id);

  })

})
</script>
