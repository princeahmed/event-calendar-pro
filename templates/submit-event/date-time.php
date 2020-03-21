<div class="ecp-date-time-meta-data">

	<div class="ecp-row">

		<div class="form-group">
			<label for="startdate"><?php _e( 'Start Date:', 'event-calendar-pro' ); ?>
				<span class="recuired text-danger"><i class="glyphicon glyphicon-asterisk"></i></span> </label>
			<input type='text' class="form-table form-control ecp-date-calendar" autocomplete="off" name="startdate" placeholder="<?php echo date( 'Y-m-d' ); ?>" required/>
		</div>

		<div class="form-group">
			<label for="starttime"><?php _e( 'Start Time:', 'event-calendar-pro' ); ?></label>
			<input type='text' class="form-table form-control  ecp-time-calendar" autocomplete="off" name="starttime" placeholder="9:00 AM" />
		</div>

	</div>

	<div class="ecp-row">

		<div class="form-group">
			<label for="enddate"><?php _e( 'End Date:', 'event-calendar-pro' ); ?>
				<span class="ecp-requirede"></span></label>
			<input type='text' class="form-table form-control ecp-date-calendar" autocomplete="off" name="enddate" placeholder="<?php echo date( 'Y-m-d' ); ?>" />
		</div>

		<div class="form-group">
			<label for="endtime"><?php _e( 'End Time:', 'event-calendar-pro' ); ?>
				<span class="ecp-requirede"></span></label>
			<input type='text' class="form-table form-control ecp-time-calendar" autocomplete="off" name="endtime" placeholder="10:00 PM" />
		</div>

	</div>

</div>

<script>

	jQuery(document).ready(function ($) {

		$(".ecp-date-calendar").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			firstDay: 7,
		});

		$(".ecp-time-calendar").timepicker({
			scrollbar: true
		});

	});

</script>

