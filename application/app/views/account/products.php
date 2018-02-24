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
						<td><input type="radio" name="plan_id" value="<?php print $dto->id ?>" /></td>
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

		</div>

	</div>

</form>
