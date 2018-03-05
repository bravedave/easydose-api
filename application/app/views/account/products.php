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
<form class="form" method="post" action="<?php url::write( 'account') ?>">
	<div class="row pb-1 pt-4">
		<div class="col col-12 col-lg-2">
			<h3 class="m-0">
				License - Buy
			</h3>
			<div class="small">
				Buy a product license for a 1 year period

			</div>

		</div>

		<div class="col col-12 col-lg-10">
			<table class="table table-striped">
				<tbody>
<?php			foreach ( $this->data->products as $dto) {
						// sys::dump( $product);	?>
					<tr>
						<td><input type="radio" name="product_id" required value="<?php print $dto->id ?>" /></td>
						<td><?php printf( '%s<br />%s', $dto->name, $dto->description); ?></td>
						<td><?php print $dto->rate ?></td>
						<td><?php print $dto->term ?></td>

					</tr>
<?php			}	// foreach ( $this->data->products as product)	?>

				</tbody>

			</table>

		</div>

	</div>

	<div class="row py-1">
		<div class="offset-2 col-10">
			<input type="submit" name="action" class="btn btn-primary" value="buy product" />
			<input type="submit" name="action" class="btn btn-outline-secondary" value="generate invoice" />

		</div>

	</div>

</form>
<script>
$(document).ready( function() {
	$('input[type="submit"][value="generate invoice"]').on( 'click', function( e) {
		var product = $('input[type="radio"][name="product_id"]:checked').val();

		if ( 'undefined' != typeof product) {
			e.stopPropagation(); e.preventDefault();

			window.location.href = _brayworth_.url('account/createinvoice/' + product);

		}

	})

})
</script>
