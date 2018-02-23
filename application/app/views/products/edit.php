<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<form class="form" method="post" action="<?php url::write('products/') ?>">
  <input type="hidden" name="id" value="<?php print $this->data->dto->id ?>" />

  <div class="row">
    <div class="col col-3 py-1">
      id
    </div>

    <div class="col col-9 py-1">
      <input type="text" class="form-control" name="<?php uniqid('ed_') ?>" value="<?php print $this->data->dto->id ?>" readonly placeholder="new product" />

    </div>

  </div>

  <div class="row">
    <div class="col col-3 py-1">
      name
    </div>

    <div class="col col-9 py-1">
      <input type="text" class="form-control" name="name" value="<?php print $this->data->dto->name ?>" placeholder="product" />

    </div>

  </div>
  <div class="row">
    <div class="col col-3 py-1">
      description
    </div>

    <div class="col col-9 py-1">
      <input type="text" class="form-control" name="description" value="<?php print $this->data->dto->description ?>" required placeholder="description" />

    </div>

  </div>
  <div class="row">
    <div class="col col-3 py-1">
      rate
    </div>

    <div class="col col-9 py-1">
      <input type="text" class="form-control" name="rate" value="<?php print $this->data->dto->rate ?>" required placeholder="rate" />

    </div>

  </div>
  <div class="row">
    <div class="col col-3 py-1">
      term
    </div>

    <div class="col col-9 py-1">
      <input type="text" class="form-control" name="term" value="<?php print $this->data->dto->term ?>" placeholder="term" />

    </div>

  </div>

  <div class="row">
    <div class="col offset-3 col-9 py-1">
      <input type="submit" class="btn btn-primary" name="action" value="save" />

    </div>

  </div>

</form>
