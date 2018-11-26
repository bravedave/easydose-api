<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<ul class="menu">
  <?php if ( isset( $this->data->dto) && $this->data->dto->id) {  ?>
    <li><a href="<?php url::write('users/view/' . $this->data->dto->id) ?>"><h4>user #<?= $this->data->dto->id ?></h4></a></li>
  <?php }
  else {  ?>
    <li><h4>users</h4></li>
  <?php } // if ( $this->data->dto->id)  ?>
  <li><a href="<?php url::write('users') ?>">list</a></li>
  <li><a href="<?php url::write('users/edit') ?>">new</a></li>

</ul>
<?php
  $this->load('main-index');
