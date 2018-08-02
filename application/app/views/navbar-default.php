<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	DO NOT change this file
	Copy it to <application>/app/views/ and modify it there
	*/	?>
	<nav class="navbar navbar-expand-md navbar-dark bg-primary sticky-top d-print-none" role="navigation" >
		<div class="navbar-brand">
			<?php print $this->data->title ?>

		</div>

		<button class="navbar-toggler" type="button" data-toggle="collapse"
		 	data-target="#navbarToggler"
			aria-controls="navbarToggler"
			aria-expanded="false"
			aria-label="Toggle navigation">

			<span class="navbar-toggler-icon"></span>

 		</button>

		<div class="collapse navbar-collapse" id="navbarToggler">
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<?php printf( '<a href="%s" class="nav-link"><i class="fa fa-fw fa-home"></i>Home</a>', \url::$URL);	?>

				</li>

				<li class="nav-item"><a class="nav-link" href="<?php url::write('account'); ?>">My Account</a></li>
				<?php if ( \sys::lockdown()) {	?>
					<li class="nav-item"><a class="nav-link" href="<?php url::write('logout') ?>">Logout</a></li>
					
				<?php } // if ( \sys::lockdown())	?>

			</ul>

		</div>

	</nav>
