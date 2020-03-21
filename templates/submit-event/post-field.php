<div class="ecp-event-info row">

	<div class="col-md-12">
		<h3>Event Info</h3>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label for="ecp_event_title"><?php _e( 'Name of event', 'event-calendar-pro' ); ?></label>
			<input type="text" class="form-control" name="ecp_event_title" id="ecp_event_title" placeholder="Event title">
		</div>

		<div class="form-group">
			<label><?php _e( 'Category', 'event-calendar-pro' ); ?></label> <br>
			<?php
			$i = 0;
			foreach ( ecp_get_all_event_categories( 'term_id' ) as $id => $name ) {
				echo sprintf( '<label class="radio-inline"><input type="radio" name="ecp_event_category[%3$d]" value="%d"> %s</label>', $id, $name, $i );
				$i++;
			}

			?>

		</div>

		<div class="form-group">
			<label for="ecp_event_desc"><?php _e( 'Description', 'event-calendar-pro' ); ?></label>
			<textarea name="ecp_event_desc" id="ecp_event_desc" rows="10" class="form-control" placeholder="Event Description"></textarea>
		</div>

		<div class="form-group">
			<label for="ecp_event_image"><?php _e( 'Image', 'event-calendar-pro' ); ?></label>

			<?php if ( current_user_can( 'upload_files' ) ) { ?>
				<a href="#" class="ecp-upload-img btn btn-primary">Browse</a>
			<?php } else { ?>
				<input type="file" name="ecp_event_image" id="ecp_event_image" class="form-control">
			<?php } ?>

			<img src="" class="ecp-uploaded-image img-responsive"> <input type="hidden" name="_thumbnail_id" value="">
			<a href="#" class="ecp-remove-image btn btn-danger" style="display: none">Remove</a>
		</div>
	</div>
</div>
