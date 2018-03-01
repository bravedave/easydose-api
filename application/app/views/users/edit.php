<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="container-fluid">
	<form method="post" class="form" data-role="user-form" action="<?php url::write('users') ?>">
		<input type="hidden" name="id" value="<?php print $this->data->dto->id ?>" />

		<div class="row form-group">
			<div class="col col-3">UserName</div>
			<div class="col col-8">
				<input type="text" name="username" class="form-control" placeholder="username"
					value="<?php print $this->data->dto->username ?>" required
					<?php if ( $this->data->dto->id) print 'disabled'; ?> />

			</div>

		</div>

		<div class="row form-group">
			<div class="col col-3">Name</div>
			<div class="col col-8">
				<input type="text" name="name" class="form-control" placeholder="name" required
					autofocus autocomplete="name" value="<?php print $this->data->dto->name ?>" />

			</div>

		</div>

		<div class="row form-group">
			<div class="col col-3">Email</div>
			<div class="col col-8">
				<input type="text" name="email" class="form-control" placeholder="@" autocomplete="email"
					value="<?php print $this->data->dto->email ?>" required />

			</div>

		</div>

		<div class="row form-group">
			<div class="col offset-3 col-8 form-check">
				<label class="form-check-label">
					<input type="checkbox" name="admin" class="form-check-input"
					<?php if ( $this->data->dto->admin) print "checked" ?> value="1" />
					Administrator
				</label>

			</div>

		</div>

		<div class="row form-group">
			<div class="col col-3">Password</div>
			<div class="col col-8">
				<input type="password" name="pass" class="form-control" placeholder="password - if you want to change it .." autocomplete="new-password" />

			</div>

		</div>

		<div class="row form-group">
			<div class="col col-8 offset-3">
				<input  class="btn btn-primary" type="submit" name="action" value="save/update" />

			</div>

		</div>

	</form>

</div>
<script>
$(document).ready( function() {
	var f = $('form.form[data-role="user-form"]');
	f.on( 'submit', function() {
		var p = $('input[name="pass"]').val();
		if ( p.length > 0 && p.length < 3) {
			$('body').growlError('password must be 3 or more characters');
			$('input[name="pass"]').focus().select();
			return ( false);

		}

		return ( true);

	})

});
</script>
