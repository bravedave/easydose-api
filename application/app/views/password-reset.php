<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>
<form class="form" method="post" action="<?php url::write('recover') ?>">
  <input type="hidden" name="guid" value="<?php print $this->data->guid ?>" />
  <div class="container">
    <div class="row">
      <div class="col col-3 p-1">
        New Password
      </div>
      <div class="col col-9 p-1">
        <input class="form-control" type="password" name="password" placeholder="new-password" />

      </div>

    </div>

    <div class="row">
      <div class="col offset-3 col-9 p-1">
        <input class="btn btn-primary" type="submit" name="action" value="reset password" />

      </div>

    </div>

  </div>

</form>
