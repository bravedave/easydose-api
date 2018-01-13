<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>

<ul class="menu">
  <li><h3>contents</h3></li>
  <li><a href="<?php url::write(); ?>">home</a></li>
<?php if ( currentUser::isAdmin()) { ?>
  <li><a href="<?php url::write('sites'); ?>">sites</a></li>
  <li><a href="<?php url::write('guid'); ?>">guid</a></li>
<?php } // if currentUser::isAdmin() ?>
  <li><a href="<?php url::write('account'); ?>">my account</a></li>
  <li>&nbsp;</li>
  <li><h3>maintenance</h3></li>
  <li><a href="<?php url::write('users'); ?>">users</a></li>
  <li><a href="<?php url::write('settings'); ?>">settings</a></li>
  <li><a href="<?php url::write('plans'); ?>">plans</a></li>
  <li><a href="<?php url::write('home/dbinfo'); ?>">dbinfo</a></li>
  <li><hr /></li>
  <li><a href="<?php url::write('docs'); ?>">docs</a></li>

</ul>
