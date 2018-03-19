<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
	<form method="post" data-role="user-form" action="<?php url::write('users') ?>">
		<input type="hidden" name="id" value="<?php print $this->data->dto->id ?>" />

		<div class="form-group row">
			<div class="col-3">UserName</div>
			<div class="col-8">
				<input type="text" name="username" class="form-control" placeholder="username"
				value="<?php print $this->data->dto->username ?>" required
				<?php if ( $this->data->dto->id) print 'disabled'; ?>
				<?php if ( $this->data->readonly) print 'readonly'; ?> />

			</div>

		</div>

		<div class="form-group row">
			<div class="col-3">Name</div>
			<div class="col-8">
				<input type="text" name="name" class="form-control" placeholder="name" required
				autofocus autocomplete="name" value="<?php print $this->data->dto->name ?>"
				<?php if ( $this->data->readonly) print 'readonly'; ?> />

			</div>

		</div>

<<<<<<< HEAD
		<div class="row form-group">
			<div class="col col-3">Email</div>
			<div class="col col-8">
				<input type="email" name="email" class="form-control" placeholder="@" autocomplete="email"
					value="<?php print $this->data->dto->email ?>" required
					<?php if ( $this->data->readonly) print 'readonly'; ?> />
=======
		<div class="form-group row">
			<div class="col-3">Email</div>
			<div class="col-8">
				<input type="text" name="email" class="form-control" placeholder="@" autocomplete="email"
				value="<?php print $this->data->dto->email ?>" required
				<?php if ( $this->data->readonly) print 'readonly'; ?> />
>>>>>>> 2d204af702ea9ef6a31346a129b73ddf0c3cded3

			</div>

		</div>

		<?php if ( $this->data->readonly) {
			/* create a tab panel layout
			*/
			?>

			<ul class="nav nav-tabs">
				<li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#license">License</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#detail">Account</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoices">Invoices</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#guid">GUID</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sites">Sites</a></li>

			</ul>

			<div class="tab-content">
				<div id="license" class="tab-pane fade in active show">
					<div class="row">
						<div class="col p-0">
							<?php $this->load('licenses') ?>

						</div>

					</div>

				</div>

				<div id="invoices" class="tab-pane fade in">
					<div class="row">
						<div class="col p-0">
							<?php $this->load('invoices') ?>

						</div>

					</div>

				</div>

				<div id="guid" class="tab-pane fade in">
					<div class="row">
						<div class="col p-0">
							<?php $this->load('guid') ?>

						</div>

					</div>

				</div>

				<div id="sites" class="tab-pane fade in">
					<div class="row">
						<div class="col p-0">
							<?php $this->load('sites') ?>

						</div>

					</div>

				</div>

				<div id="detail" class="tab-pane fade in">

				<?php } ?>

				<div class="row py-1">
					<div class="col-3">Business Name</div>
					<div class="col-9">
						<input type="text" name="business_name" class="form-control" placeholder="business name"
						value="<?php print $this->data->dto->business_name ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<div class="row py-1">
					<div class="col-3">Address</div>
					<div class="col-9">
						<input type="text" name="street" class="form-control" placeholder="street"
						autocomplete="address-line1" value="<?php print $this->data->dto->street ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<div class="row py-1">
					<div class="offset-3 col-9">
						<input type="text" name="town" class="form-control" placeholder="town"
						autocomplete="address-level2" value="<?php print $this->data->dto->town ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<div class="row py-1">
					<div class="offset-3 col-5">
						<input type="text" name="state" class="form-control" placeholder="state"
						autocomplete="address-level3" value="<?php print $this->data->dto->state ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

					<div class="col-4">
						<input type="text" name="postcode" class="form-control" placeholder="postcode"
						autocomplete="postal-code" value="<?php print $this->data->dto->postcode ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<div class="row py-1">
					<div class="col-3">ABN</div>
					<div class="col-9">
						<input type="text" name="abn" class="form-control"
						placeholder="ABN" value="<?php print $this->data->dto->abn ?>"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<div class="row form-group">
					<div class="offset-3 col-8 form-check">
						<label class="form-check-label">
							<input type="checkbox" name="admin" class="form-check-input"
							<?php if ( $this->data->dto->admin) print "checked" ?> value="1"
							<?php if ( $this->data->readonly) print 'disabled'; ?> />
							Administrator
						</label>

					</div>

				</div>

				<div class="row form-group">
					<div class="col-3">Password</div>
					<div class="col-8">
						<input type="password" name="pass" class="form-control"
						placeholder="password - if you want to change it .."
						autocomplete="new-password"
						<?php if ( $this->data->readonly) print 'readonly'; ?> />

					</div>

				</div>

				<?php if ( $this->data->readonly) {	?>

				</div><!-- div id="detail" -->

			</div><!-- div class="tab-content" -->

		<?php } ?>

		<div class="row form-group">
			<div class="col-8 offset-3">
				<?php if ( $this->data->readonly) { ?>
					<a href="<?php url::write('users/edit/' . $this->data->dto->id) ?>" class="btn btn-primary btn-link">edit</a>
					<a href="<?php url::write('users/createinvoice/' . $this->data->dto->id) ?>" class="btn btn-outline-secondary">generate invoice</a>
				<?php }	// if ( $this->data->readonly)
				else { ?>
					<input  class="btn btn-primary" type="submit" name="action" value="save/update" />
					<a href="<?php url::write('users/view/' . $this->data->dto->id) ?>" class="btn btn-primary btn-link">cancel</a>
				<?php }	// if ( $this->data->readonly) ?>

			</div>

		</div>

	</form>

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
