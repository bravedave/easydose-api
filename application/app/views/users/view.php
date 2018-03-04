<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="container-fluid">
	<div class="row py-1">
		<div class="col col-3">UserName</div>
		<div class="col col-8">
			<?php print $this->data->dto->username ?>

		</div>

	</div>

	<div class="row py-1">
		<div class="col col-3">Name</div>
		<div class="col col-8">
			<?php print $this->data->dto->name ?>

		</div>

	</div>

	<div class="row py-1">
		<div class="col col-3">Email</div>
		<div class="col col-8">
			<?php print $this->data->dto->email ?>

		</div>

	</div>

	<div class="row py-1">
		<div class="col offset-3 col-8 form-check">
			<?php if ( $this->data->dto->admin) print "administrator" ?>

		</div>

	</div>

	<div class="row py-1">
		<div class="col offset-3 col-8 form-check">
			<a href="<?php url::write( 'users/edit/' . $this->data->dto->id) ?>" class="btn btn-primary btn-link">edit</a>

		</div>

	</div>

</div>
