<?php
/**
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
 * */

$account = $this->data->account;
// sys::dump( $account);
?>
<form method="POST" action="<?= strings::url( $this->route ) ?>" autocomplete="off">
	<input type="hidden" name="action" value="save-account" />

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">server:</label>
			<input type="text" class="form-control" name="server"
				id="<?= $uid ?>"
				required value="<?= $account->server ?>" />

		</div>

	</div>

<?php	if ( 'imap' == dvc\mail\config::$MODE) {	?>
	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">server type:</label>
			<select class="form-control" name="type" id="<?= $uid ?>">
				<option value="">linux</option>
				<option value="exchange" <?php if ( 'exchange' == $account->type) print 'selected'  ?>>exchange</option>

			</select>

		</div>

	</div>

<?php	}	// if ( 'imap' == dvc\mail\config::$MODE)	?>

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">name:</label>
			<input type="text" class="form-control" name="name"
				autocomplete="off"
				id="<?= $uid ?>"
				required value="<?= $account->name ?>" />

		</div>

	</div>

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">email:</label>
			<input type="email" class="form-control" name="email"
				autocomplete="off"
				id="<?= $uid ?>"
				required value="<?= $account->email ?>" />

		</div>

	</div>

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">username:</label>
			<input type="text" class="form-control" name="username"
				autocomplete="off"
				id="<?= $uid ?>"
				required value="<?= $account->username ?>" />

		</div>

	</div>

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">password:</label>
			<div class="input-group">
				<input class="form-control" name="password" autocomplete="new-password" id="<?= $uid ?>" />
				<div class="input-group-append" id="<?= $uid ?>-control">
					<div class="input-group-text">
						<i class="fa fa-eye"></i>

					</div>

				</div>

				<script>
				$(document).ready( function() {
					$('#<?= $uid ?>')
					.attr('type','password')
					.val('--------');

				});
				</script>

			</div>

			<script>
			$(document).ready( function() {
				$('#<?= $uid ?>-control').on( 'click', function( e) {
					let _me = $(this);
					let fld = $('#<?= $uid ?>');

					if ( 'text' == fld.attr( 'type')) {
						fld.attr( 'type', 'password');
						$('.fa-eye-slash', _me).removeClass('fa-eye-slash').addClass('fa-eye');

					}
					else {
						fld.attr( 'type', 'text');
						$('.fa-eye', _me).removeClass('fa-eye').addClass('fa-eye-slash');

					}

				});

			});
			</script>

		</div>

	</div>

	<div class="form-group row">
		<div class="col">
			<label for="<?= $uid = strings::rand() ?>">profile:</label>
			<input type="text" class="form-control" name="profile" placeholder="default profile"
				id="<?= $uid ?>"
				value="<?= $account->profile ?>" />

		</div>

	</div>

	<div class="row">
		<div class="col text-right">
			<button class="btn btn-primary">update account</button>

		</div>

	</div>

</form>