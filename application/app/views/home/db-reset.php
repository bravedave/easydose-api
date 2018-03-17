<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<form method="POST" action="<?php url::write('settings') ?>">
    <div class="form-group">
      <label for="confirmation">Email address</label>
      <input type="text" class="form-control" name="confirmation"
        id="confirmation" aria-describedby="resetHelp">
      <small id="resetHelp" class="form-text text-muted">Please type <strong><em>Reset Confirmed</em></strong> (Case Sensitive) to activate the reset.</small>

    </div>

    <input type="submit" class="btn btn-primary" name="action" value="reset database" />

</form>
