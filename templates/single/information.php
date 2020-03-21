<?php
global $post;
$name             = ecp_get_event_meta($post->ID, 'name');
$phone            = ecp_get_event_meta($post->ID, 'phone');
$contactname      = ecp_get_event_meta($post->ID, 'contactname');
$contactmail      = ecp_get_event_meta($post->ID, 'contactmail');
$url              = ecp_get_event_meta($post->ID, 'url');
$age              = ecp_get_event_meta($post->ID, 'age');
?>
<div class="ecp-single-event-info">
	<h3 class="title">Additional Information</h3>
	<?php echo !empty($name) ? '<div class="sponsor"><h4>Sponsor</h4><span>' . $name . '</span></div>' : '' ?>
	<?php echo !empty($phone) ? '<div class="phone"><h4>Phone</h4><span>' . $phone . '</span></div>' : '' ?>
	<?php echo !empty($contactmail) ? '<div class="email"><h4>Email</h4><span>' . $contactmail . '</span></div>' : '' ?>
	<?php echo !empty($contactname) ? '<div class="contactname"><h4>Name</h4><span>' . $contactname . '</span></div>' : '' ?>
	<?php echo !empty($url) ? '<div class="url"><h4>Website</h4><span><a href="' . $url . '">' . $url . '</a></span></div>' : '' ?>
	<?php echo !empty($cost) ? '<div class="cost"><h4>Cost</h4><span>' . $cost . '</span></div>' : '' ?>
	<?php echo !empty($age) ? '<div class="age"><h4>Ages</h4><span>' . $age . '</span></div>' : '' ?>
</div>
