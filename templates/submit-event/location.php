<div class="ecp-location-meta-data">
	<!--location-->
	<div class="form-group">
		<label for="location"><?php _e( 'Location:', 'event-calendar-pro' ); ?>
			<span class="ecp-requirede"></span></label>
		<input type="text" class="form-table form-control" name="location" id="location">
	</div><!--End location-->

	<!--Address-->
	<label for="address"><?php _e( 'Address:', 'event-calendar-pro' ); ?></label>
	<div class="ecp-row">
		<div class="form-group">
			<input type="text" class="form-table form-control" name="address" id="address" placeholder="Street 1">
		</div>
		<div class="form-group">
			<input type="text" class="form-table form-control" name="address2" id="address2" placeholder="Street 2">
		</div>
	</div><!--end address-->


	<div class="ecp-row">
		<!--City-->
		<div class="form-group">
			<label for="city"><?php _e( 'City:', 'event-calendar-pro' ); ?></label>
			<input type="text" class="form-table form-control" name="city" id="city">
		</div>

		<!--State-->
		<div class="form-group">
			<label for="state"><?php _e( 'State:', 'event-calendar-pro' ); ?></label>
			<select name="state" id="state" class="form-table form-control">
				<?php
				$states = ecp_get_states();
				if ( ! empty( $states ) ) {
					echo '<option value="">Select..</option>';
					foreach ( $states as $code => $st ) {
						echo sprintf( '<option value="%s">%s</option>', $code, $st['state'] );
					}
				} else {
					echo '<option value="">Please, Add states form Events Settings Page</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="ecp-row">
		<!--Zipcode-->
		<div class="form-group">
			<label for="zip"><?php _e( 'Zipcode:', 'event-calendar-pro' ); ?></label>
			<input type="text" name="zip" id="zip" class="form-table form-control">
		</div>

		<!--Country-->
		<div class="form-group">
			<label for="country"><?php _e( 'Country:', 'event-calendar-pro' ); ?></label>
			<select name="country" id="country" class="form-table form-control">
				<?php
				$countries = ecp_get_countries();
				if ( ! empty( $countries ) ) {
					echo '<option value="">Select..</option>';
					foreach ( $countries as $code => $name ) {
						echo sprintf( '<option value="%s">%s</option>', $code, $name );
					}
				} else {
					echo '<option value="">Please, Add countries form Events Settings Page</option>';
				}
				?>
			</select>
		</div>
	</div>
</div>
