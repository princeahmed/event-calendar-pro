<?php

global $post;

$cost             = ecp_get_event_meta($post->ID, 'cost');
$age              = ecp_get_event_meta($post->ID, 'age');
$featured         = ecp_get_event_meta($post->ID, 'featured');
$readersubmitted  = ecp_get_event_meta($post->ID, 'readersubmitted');
$photodescription = ecp_get_event_meta($post->ID, 'photodescription');

?>
<div class="ecp-information-meta-data">
	<div class="ecp-row">
		<div class="form-group">
			<label for="cost"><?php _e('Cost:', 'event-calendar-pro'); ?></label> <input type="text" name="cost"
				id="cost" class="form-table form-control" value="<?php echo $cost; ?>">
		</div>
	</div>
	<div class="ecp-row">
		<div class="form-group">
			<label for="age"><?php _e('Ages:', 'event-calendar-pro'); ?></label> <input type="text" name="age" id="age"
				class="form-table form-control" value="<?php echo $age; ?>">
		</div>
	</div>

	<?php do_action('ecp_information_meta_field', $post->ID); ?>
	
<!--	<div class="ecp-row">-->
<!--		<div class="form-group">-->
<!--			<label for="photodescription">--><?php //_e('Photo description:', 'event-calendar-pro'); ?><!--</label> <input-->
<!--				type="text" name="photodescription" id="photodescription" class="form-table form-control"-->
<!--				value="--><?php //echo $photodescription; ?><!--" placeholder="Photo credit: Gaël Della Valle">-->
<!--		</div>-->
<!--	</div>-->
	<?php if(is_admin()){ ?>
	<div class="ecp-row">
		<label class="control-label">Featured:&emsp;</label> <span class="param-value yesno">
			<input type="radio" name="featured" value="no" <?php echo($featured != 'yes' ? 'checked' : ''); ?>>
			<label class="radio-label">No</label>
			<input type="radio" name="featured" value="yes" <?php echo($featured == 'yes' ? 'checked' : ''); ?>>
			<label class="radio-label">Yes</label>
		</span>
	</div>

	<div class="ecp-row">
		<label class="control-label">Reader submitted:&emsp;</label> <span class="param-value yesno">
			<input type="radio" name="readersubmitted"
				value="no" <?php echo($readersubmitted != 'yes' ? 'checked' : ''); ?>>
			<label class="radio-label">No</label>
			<input type="radio" name="readersubmitted"
				value="yes" <?php echo($readersubmitted == 'yes' ? 'checked' : ''); ?>>
			<label class="radio-label">Yes</label>
		</span>
	</div>

	<?php }else{ echo '<input type="hidden" name="readersubmitted" value="no">'; }?>

</div>
