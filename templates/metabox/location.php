<?php
global $post;

$location = ecp_get_event_meta($post->ID, 'location');
$address = ecp_get_event_meta($post->ID, 'address');
$address2 = ecp_get_event_meta($post->ID, 'address2');
$city = ecp_get_event_meta($post->ID, 'city');
$state = ecp_get_event_meta($post->ID, 'state');
$country = ecp_get_event_meta($post->ID, 'country');
$zip = ecp_get_event_meta($post->ID, 'zip');

$countries = unserialize(get_option('countries'));
$states = unserialize(get_option('states'));

?>
<div class="ecp-location-meta-data">
	<div class="form-group">
		<label for="location"><?php _e('Location:', 'event-calendar-pro'); ?><span class="ecp-requirede"></span></label>
		<input type="text" class="form-table form-control" name="location" id="location" value="<?php echo $location; ?>">
	</div>

	<label for="address"><?php _e('Address:', 'event-calendar-pro'); ?></label>
	<div class="ecp-row">
		<div class="form-group">
			<input type="text" class="form-table form-control" name="address" id="address" placeholder="Street 1"
				   value="<?php echo $address; ?>">
		</div>
		<div class="form-group">
			<input type="text" class="form-table form-control" name="address2" id="address2" placeholder="Street 2"
				   value="<?php echo $address2; ?>">
		</div>
	</div>

	<div class="ecp-row">

		<div class="form-group">
			<label for="city"><?php _e('City:', 'event-calendar-pro'); ?></label>
			<input type="text" class="form-table form-control" name="city" id="city"
				   value="<?php echo $city; ?>">
		</div>

		<div class="form-group">

			<label for="state"><?php _e('State:', 'event-calendar-pro'); ?></label>
			<select name="state" id="state" class="form-table form-control">
				<?php
				if (!empty($states)) {
					echo '<option value="">Select..</option>';
					foreach ($states as $code => $st) { ?>
						<option
							value="<?php echo $code ?>" <?php echo ($code == $state) ? 'selected' : '' ?>><?php echo $st['state'] ?></option>
					<?php }
				} else { ?>
					<option value="">Please, Add states form Events Settings Page</option>
				<?php } ?>
			</select>

		</div>
	</div>

	<div class="ecp-row">

		<div class="form-group">
			<label for="zip"><?php _e('Zipcode:', 'event-calendar-pro'); ?></label>
			<input type="text" name="zip" id="zip" class="form-table form-control" value="<?php echo $zip; ?>">
		</div>

		<div class="form-group">
			<label for="country"><?php _e('Country:', 'event-calendar-pro'); ?></label>
			<select name="country" id="country" class="form-table form-control">
				<?php if (!empty($countries)) { ?>
				<option value="">Select..</option>
				<?php foreach ($countries as $code => $name) { ?>
					<option
						value="<?php echo $code ?>" <?php echo ($code == $country) ? 'selected' : '' ?>><?php echo $name ?></option>
				<?php }}else{ ?>
					<option value="">Please, Add countries form Events Settings Page</option>
			<?php } ?>
			</select>
		</div>

	</div>

</div>
