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
  <div class="row py-1">
    <div class="col col-12 col-lg-2">
      <h3 class="m-0">
        Workstation Plans
      </h3>
      <div class="small">
        calculated yearly, paid monthly
      </div>
    </div>

    <div class="col col-12 col-lg-10">
      <table class="table table-striped">
        <tbody>
<?php			foreach ( $this->data->plansWKS as $plan) {	?>
          <tr>
            <td><input type="radio" name="plan_id" value="<?php print $plan->paypal_id ?>" /></td>
            <td><?php printf( '%s<br />%s', $plan->name, $plan->description ) ?></td>
            <td><?php print $plan->rate ?></td>
            <td><?php print $plan->frequency ?></td>

          </tr>
<?php			}	// foreach ( $this->data->plans as $plan)	?>

        </tbody>

      </table>

    </div>

  </div>

  <div class="row py-1">
    <div class="offset-lg-2 col-12 col-lg-10">
      <input type="submit" name="action" class="btn btn-primary" value="subscribe" />

    </div>

  </div>

</form>
