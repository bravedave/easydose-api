<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 *      http://creativecommons.org/licenses/by/4.0/
 *
*/	?>
<nav class="navbar navbar-expand-md navbar-dark bg-primary sticky-top d-print-none py-0" role="navigation" >

	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarToggler"
		aria-controls="navbarToggler"
		aria-expanded="false"
		aria-label="Toggle navigation">

		<span class="navbar-toggler-icon"></span>

	</button>

	<div class="collapse navbar-collapse" id="navbarToggler">
		<ul class="navbar-nav mr-auto">
		<?php
		if ( \sys::lockdown()) {
			if ( \currentUser::isAdmin()) {	?>
			<li class="nav-item">
				<?php printf( '<a href="%s" class="nav-link"><i class="fa fa-fw fa-home"></i></a>', \url::$URL);	?>

			</li>

				<?php
				if ( dvc\mail\config::$ENABLED && dvc\mail\config::$ENABLED) {	?>
			<li class="nav-item">
				<a class="nav-link" href="<?= strings::url('mail/webmail'); ?>" title="inbox"><i class="fa fa-inbox"></i></a>

			</li>
				<?php
				}	// if ( dvc\mail\config::$ENABLED)	?>

			<li class="nav-item"><a class="nav-link" href="<?= strings::url('account'); ?>">My Account</a></li>

			<?php
			} else {	?>
			<li class="nav-item">
				<a class="nav-link" href="<?= strings::url('account'); ?>"><i class="fa fa-fw fa-home"></i>Home</a>

			</li>

		<?php
			}	// if ( \currentUser::isAdmin())	?>

			<li class="nav-item"><a class="nav-link" href="<?= strings::url('logout') ?>">Logout</a></li>

		<?php
		} else {	?>
			<li class="nav-item">
				<?php printf( '<a href="%s" class="nav-link"><i class="fa fa-fw fa-home"></i>Home</a>', \url::$URL);	?>

			</li>

			<li class="nav-item"><a class="nav-link" href="<?= strings::url('account'); ?>">My Account</a></li>

		<?php
		}	// if ( \sys::lockdown())	?>

		</ul>

	</div>

	<?php if ( \currentUser::isAdmin()) {
		printf( '<div class="navbar-brand">%s</div>', $this->data->title);

	}	?>

</nav>
