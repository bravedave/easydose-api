<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		viewer class for user table

	security:
	 	Ordinary Authenticated user - non admin

	*/	?>
<form class="form" method="post" action="<?php url::write( 'account') ?>">
	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Username</div>
		<div class="col-10 col-sm-4">
			<input type="text" class="form-control" placeholder="user name" value="<?php print currentUser::username() ?>" readonly />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Name</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="name" class="form-control" placeholder="name" value="<?php print currentUser::name() ?>" autofocus />

		</div>

	</div>

	<div class="row py-1">
		<div class="col-sm-2 d-none d-sm-block">Email</div>
		<div class="col-12 col-sm-8">
			<input type="text" name="email" class="form-control" placeholder="email" value="<?php print currentUser::email() ?>" <?php if ( !currentUser::isAdmin()) print 'readonly' ?> />

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-sm-2 col col-sm-10">
			<input type="submit" name="action" class="btn btn-primary" value="update" />

		</div>

	</div>

</form>
