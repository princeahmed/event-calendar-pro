<?php
$countries = ecp_get_countries();
$states    = ecp_get_states();

?>
<h3>States</h3>
<?php if ($states) { ?>
	<table class="widefat">
		<thead>
		<tr>
			<th>State Name</th>
			<th>State Code</th>
			<th>Country Code</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($states as $code => $state) { ?>
			<tr>
				<td><?php echo $state['state'] ?></td>
				<td><?php echo $code ?></td>
				<td><?php echo $state['country_code'] ?></td>
				<td>
					<button type="submit" name="delete" value="<?php echo $code ?>" class="button button-link-delete"
						onclick="return confirm('Are you sure you want to delete ?');">Delete
					</button>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php } else { ?>
	<h4 class="text-center">No State Found</h4>
<?php } ?>


<h3>Add new state</h3>

<table class="widefat">
	<tr>
		<td><label for="state_name">State Name:</label></td>
		<td><input type="text" name="state_name" class="form-control" id="state_name" placeholder="New York"></td>
	</tr>
	<tr>
		<td><label for="state_code">State Code:</label></td>
		<td><input type="text" name="state_code" class="form-control" id="state_code" placeholder="NY"></td>
	</tr>
	<tr>
		<td><label for="country_code">Country Code:</label></td>
		<td><select name="country_code" id="country_code">
				<?php if (!empty($countries)) { ?>
					<option value="">Select..</option>
					<?php foreach ($countries as $code => $name) { ?>
						<option value="<?php echo $code ?>"><?php echo $name ?></option>
					<?php }
				} else { ?>
					<option value="">Please, Add countries form Countries Tab</option>
				<?php } ?>
			</select></td>
	</tr>
	<tr>
		<td colspan="2">
			<button type="submit" class="button button-primary">Add</button>
		</td>
	</tr>
</table>

