<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
 * */	?>

<div class="row">
	<div class="col pt-4">
		<ul class="list-unstyled mt-4">
			<li><h6>Index</h6></li>

<?php	if ( dvc\mail\config::$ENABLED) {

			if ( 'ews' == dvc\mail\config::$MODE) {	?>

			<li><a href="<?= strings::url( 'ews/account') ?>">account</a></li>
			<li><a href="<?= strings::url( 'ews/agenda') ?>">agenda</a></li>
			<li><a href="<?= strings::url( 'ews/webmail') ?>">webmail</a></li>

<?php		}
			elseif ( 'imap' == dvc\mail\config::$MODE) {	?>

			<li><a href="<?= strings::url( 'mail/webmail') ?>">webmail</a></li>
			<li><a href="<?= strings::url( 'imap/account') ?>">account</a></li>

<?php
				if ($profiles = dvc\imap\account::profiles()) {
					print '<ul class="list-unstyled my-4 pl-2">';
					print '<li><h6>profiles</h6></li>';
					foreach ($profiles as $profile) {
						printf( '<li href="#" data-role="load-profile" data-profile="%s">%s</li>',
							htmlspecialchars( $profile->profile),
							$profile->profile);

					}

					print '</ul>';

				}

				// sys::dump( $profiles, null, false);

			}

		}	?>

<?php		if ( class_exists( 'dvc\ews\config')) {	?>
			<li class="mt-1"><a href="<?= strings::url( 'settings') ?>">settings</a></li>

<?php		}	// if ( class_exists( 'dvc\wepm\controller'))	?>

			<li class="mt-1"><a href="<?= strings::url( $this->route . '/options') ?>">options</a></li>

		</ul>

	</div>

</div>
<script>
$(document).ready( function() {
	$('li[data-role="load-profile"]').each( function( i, el) {

		$(el).on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation();e.preventDefault();

			_brayworth_.hideContexts();

			let _el = $(this);
			let _data = _el.data();
			// console.table( _data);

			let _context = _brayworth_.context();

			_context.append( $('<a href="#">load profile</a>').on( 'click', function( e) {
				e.stopPropagation();e.preventDefault();

				_brayworth_.post({
					url : _brayworth_.url('imap'),
					data : {
						action : 'load-profile',
						profile : _data.profile,

					},

				}).then( function( d) {
					_brayworth_.growl( d);
					if ( 'ack' == d.response) {
						window.location.reload();

					}

				});

				_context.close();

			}));

			_context.append( $('<a href="#"><i class="fa fa-trash"></i>delete profile</a>').on( 'click', function( e) {
				e.stopPropagation();e.preventDefault();

				_brayworth_.post({
					url : _brayworth_.url('imap'),
					data : {
						action : 'delete-profile',
						profile : _data.profile,

					},

				}).then( function( d) {
					_brayworth_.growl( d);
					if ( 'ack' == d.response) {
						_el.remove();

					}

				});

				_context.close();

			}));

			_context.open( e);

		});;

	});

});
</script>