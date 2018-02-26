<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

  // sys::dump( $this->data->res);
  if ( $this->data->dto) {
    $dto = $this->data->dto;

  	?>
<div class="row">
  <div class="col col-3 py-1">
    id

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->id ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    payment_id

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->payment_id ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    state

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->state ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    product_id

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->product_id ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    product name

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->name ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    product description

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->description ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">

  </div>

  <div class="col col-9 col-sm-6 col-md-5 col-lg-4 py-0">
    <table class="table table-sm table-striped">
      <thead>
        <tr>
          <td class="text-center">
            cost

          </td>

          <td class="text-center">
            tax

          </td>

          <td class="text-center">
            ext.

          </td>

        </tr>

      </thead>

      <tbody>
        <tr>
          <td class="text-right">
            <?php print round( (float)$dto->value - (float)$dto->tax, 2) ?>

          </td>

          <td class="text-right">
            <?php print $dto->tax ?>

          </td>

          <td class="text-right">
            <?php print $dto->value ?>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    user_name

  </div>

  <div class="col col-9 py-1">
    <?php printf( '%s (%s)', $dto->user_name, $dto->user_id ) ?>

  </div>

</div>

<div class="row">
  <div class="col col-3 py-1">
    created / updated

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->created ?> / <?php print $dto->updated ?>

  </div>

</div>


<div class="row">
  <div class="col col-3 py-1">
    cart

  </div>

  <div class="col col-9 py-1">
    <?php print $dto->cart ?>

  </div>

</div>

<?php
  if ( $dto->state != 'approved' && date( 'Y-m-d', strtotime( $dto->created)) < date( 'Y-m-d')) {
     ?>
<div class="row">
  <div class="col offset-3 col-9 py-1">
    <button data-id="<?php print $dto->id ?>" delete class="btn btn-danger">delete</button>

  </div>

</div>
<?php
  } ?>

<script>
$(document).ready( function() {
  $('button[delete]').on( 'click', function(e) {

    var id = $(this).data('id');
    var modal = _brayworth_.modal({
      title : 'confirm',
      text : 'are you sure ?',
      buttons : {
        yes : function( e) {
            window.location.href = _brayworth_.url('payments/delete/' + id);
            modal.modal('close');

        }

      }

    })

  })

});
</script>

<?php
  }
