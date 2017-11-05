<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<form class="form" method="post" action="<?php url::write('settings') ?>">

	<div class="form-group row">
		<label class="col-2" for="name">Name</label>
		<div class="col-8">
			<input type="text" id="name" name="name" class="form-control" required autofocus />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="description">Description</label>
		<div class="col-8">
			<input type="text" id="description" name="description" class="form-control" required />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="type">Type</label>
		<div class="col-4">
			<input type="text" id="type" name="type" class="form-control" value="INFINITE" readonly />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="description">Cycles</label>
		<div class="col-4">
			<input type="text" name="cycles" class="form-control" value="0" readonly />

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="frequency">Definition</label>
		<div class="col-4">
			<select id="frequency" name="frequency" class="form-control">
				<option value="MONTH">Month</option>
				<option value="YEAR">Year</option>

			</select>

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="frequencyInterval">Payment Interval</label>
		<div class="col-4">
			<select id="frequencyInterval" name="frequencyInterval" class="form-control">
				<option value="1">Monthly</option>
				<option value="3">Quarterly</option>
				<option value="12">Yearly</option>

			</select>

		</div>

	</div>

	<div class="form-group row">
		<label class="col-2" for="value">Value</label>
		<div class="col-4">
			<input type="text" id="value" name="value" class="form-control text-right" value="$ 0.00" required />

		</div>

	</div>

	<div class="form-group row">
		<div class="offset-2 col-8">
			<input class="btn btn-primary" type="submit" name="action" value="save plan" />

		</div>

	</div>


</form>
