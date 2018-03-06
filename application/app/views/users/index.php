<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<ul class="menu">
  <li><h4>users</h4></li>
  <li><a href="<?php url::write('users') ?>">list</a></li>
  <li><a href="<?php url::write('users/edit') ?>">new</a></li>

</ul>
<?php
  $this->load('main-index');

  
