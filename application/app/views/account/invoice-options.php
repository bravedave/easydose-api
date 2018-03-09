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

	*/ ?>
<div class="row d-print-none">
  <div class="col">
    <form class="form" method="POST" action="<?php url::write('account') ?>">
      <input type="hidden" name="id" value="<?php print $this->data->invoice->id ?>" />
      <?php if ( currentUser::id() == $this->data->invoice->user_id) {  ?>
        <input type="submit" name="action" class="btn btn-primary" value="pay invoice" />

      <?php } ?>
      <?php if ( currentUser::isAdmin()) {  ?>
        <a href="#" class="btn btn-default" id="change-expiry">change expiry</a>
      <?php } ?>
      <a href="<?php url::write( sprintf( 'account/invoice/%s?send=yes', $this->data->invoice->id )) ?>" class="btn btn-default">send invoice</a>


    </form>

  </div>

</div>
<?php if ( currentUser::isAdmin()) {  ?>
<script>
$(document).ready( function() {
  $('#change-expiry').on( 'click', function( e) {
      var fld = $('<input type="date" class="form-control" value="<?php print $this->data->invoice->expires ?>" />')
      _brayworth_.modal({
        title : 'change expiry date',
        text : fld,
        width : 300,
        buttons : {
          update : function() {
            hourglass.on();
            $(this).modal('close');

            _brayworth_.post({
              url : _brayworth_.url('invoices'),
              data : {
                action : 'update-expires',
                invoice_id : <?php print $this->data->invoice->id ?>,
                expires : fld.val()

              }

            })
            .then( function( d) {
                _brayworth_.growl(d).then( function() {
                  window.location.reload();
                  hourglass.off();

                });

            })

          }

        }

      })

      $(this).blur();

  });

});
</script>
<?php } ?>
