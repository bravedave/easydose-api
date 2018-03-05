<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		viewer class for user table

	security:
	 	Ordinary Authenticated user - non admin

	*/	?>
<div class="row py-1 mt-4">
	<div class="col col-12 col-lg-2">
		<i class="fa fa-fw fa-caret-right pointer pull-right" id="show-agreements"></i>
		Agreements

	</div>

	<div class="col col-12 col-lg-10">
		<span id="show-agreements-table-ellipses" class="pointer">...</span>
		<table class="table table-striped d-none" id="show-agreements-table">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col style="width: 2em;"/>

			</colgroup>

			<thead>
				<tr>
					<td>agreement_id</td>
					<td>product</td>
					<td>state</td>
					<td colspan="2">refreshed</td>

				</tr>

			</thead>

			<tbody>
<?php
	/*
		both wokstation and active licenses will be shown here
	*/
	foreach ( $this->data->agreementsForUser as $agreement) {	?>
				<tr>
					<td><?php print $agreement->agreement_id ?></td>
					<td><?php print $agreement->product ?></td>
					<td><?php print $agreement->state ?></td>
					<td><?php print date( \config::$DATE_FORMAT, strtotime( $agreement->refreshed)) ?></td>
					<td><?php
						if ( strtolower( $agreement->state) == 'active') {
							printf( '<i class="fa fa-fw fa-times text-danger" data-agreement_id="%s" cancel-agreement></i>', $agreement->agreement_id);

						}
						else {
							print '&bull;';

						}

						?></td>

				</tr>
<?php
	}	// foreach ( $this->data->plans as $plan)	?>

			</tbody>

		</table>

	</div>

</div>
<script>
$(document).ready( function() {
  $('i[cancel-agreement]').each( function( i, el) {
    var _el = $(el);
    _el.css('cursor','pointer').on( 'click', function( e) {
      e.stopPropagation(); e.preventDefault();

      _brayworth_.modal({
        title : 'Confirm Delete',
        text : 'This will cancel your Subscription',
        buttons : {
          confirm : function( e) {
            var data = {
              action : 'unsubscribe',
              agreement_id : _el.data('agreement_id'),

            }

            _brayworth_.post({
              url: _brayworth_.url('account/'),
              data : data

            })
            .then( function( d) {
              _brayworth_.growl( d);
              if ( 'ack' == d.response) {
                hourglass.on();
                window.location.reload();

              }

            });

            // alert( 'right o');
            $(this).modal('close');

          }

        }

      });

    });

  });

	$('#show-agreements, #show-agreements-table-ellipses').on( 'click', function(e) {
		var t = $('#show-agreements-table');
		if ( t.hasClass( 'd-none')) {
			t.removeClass( 'd-none');
			$('#show-agreements-table-ellipses').addClass( 'd-none');
			$('#show-agreements').removeClass('fa-caret-right').addClass('fa-caret-down');

		}
		else {
			t.addClass( 'd-none');
			$('#show-agreements-table-ellipses').removeClass( 'd-none');
			$('#show-agreements').removeClass('fa-caret-down').addClass('fa-caret-right');

		}

	})

});
</script>
