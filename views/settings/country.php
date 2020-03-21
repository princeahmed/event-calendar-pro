<?php

$countries = ecp_get_countries();

?>
<div class="row">
	<div class="col-md-7">
		<h3>Countries</h3>
		<hr>
		<?php if ($countries) { ?>
			<table class="widefat" cellspacing="0">
				<thead>
				<tr>
					<th>Country</th>
					<th>Country Code</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($countries as $code => $name) { ?>
					<tr>
						<td><?php echo $name ?></td>
						<td><?php echo $code ?></td>
						<td>
							<button type="submit" name="delete" value="<?php echo $code ?>"
								class="button button-link-delete"
								onclick="return confirm('Are you sure you want to delete ?');">Delete
							</button>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<h4 class="text-center">No Country Found</h4>
		<?php } ?>
	</div>
	<h3>Add new country</h3>
	<table class="widefat">
		<tr>
			<td><label for="country">Country:</label></td>
			<td><input type="text" name="country" class="" id="country" placeholder="Canada"></td>
		</tr>
		<tr>
			<td><label for="country_code">Country Code:</label></td>
			<td><input type="text" name="country_code" class="" id="country_code" placeholder="CA"></td>
		</tr>
		<tr>
			<td colspan="2">
				<button type="submit" class="button button-primary">Add</button>
			</td>
		</tr>
	</table>
</div>
