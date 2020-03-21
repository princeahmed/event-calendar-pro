<?php
global $post;

$name        = ecp_get_event_meta($post->ID, 'name');
$phone       = ecp_get_event_meta($post->ID, 'phone');
$contactname = ecp_get_event_meta($post->ID, 'contactname');
$contactmail = ecp_get_event_meta($post->ID, 'contactmail');
$url         = ecp_get_event_meta($post->ID, 'url');

?>
<div class="ecp-sponsor-meta-data">
	<div class="ecp-row">
		<div class="form-group">
			<label for="name"><?php _e('Name:', 'event-calendar-pro'); ?></label> <input type="text" name="name"
				id="name" class="form-table form-control" value="<?php echo $name; ?>">
		</div>
	</div>
	<div class="ecp-row">
		<div class="form-group">
			<label for="phone"><?php _e('Phone:', 'event-calendar-pro'); ?></label> <input type="text" name="phone"
				id="phone" class="form-table form-control" value="<?php echo $phone; ?>">
		</div>
	</div>
	<div class="ecp-row">
		<div class="form-group">
			<label for="contactname"><?php _e('Contact Name:', 'event-calendar-pro'); ?></label> <input type="text"
				name="contactname" id="contactname" class="form-table form-control" value="<?php echo $contactname; ?>">
		</div>
	</div>
	<div class="ecp-row">
		<div class="form-group">
			<label for="contactmail"><?php _e('Contact Email:', 'event-calendar-pro'); ?></label> <input type="text"
				name="contactmail" id="contactmail" class="form-table form-control" value="<?php echo $contactmail; ?>">
		</div>
	</div>
	<div class="ecp-row">
		<div class="form-group">
			<label for="url"><?php _e('Website:', 'event-calendar-pro'); ?></label> <input type="text" name="url"
				id="url" class="form-table form-control" value="<?php echo $url; ?>">
		</div>
	</div>
</div>
