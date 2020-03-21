<?php

global $post;
$photodescription = ecp_get_event_meta( $post->ID, 'photodescription' );

?>
<h3 class="title">Description</h3>
<div class="ecp-single-event-body">
	<div class="event-image">
		<?php echo ecp_get_thumbnail( $post->ID ); ?><?php echo $photodescription ? "<p>{$photodescription}</p>" : '' ?>
	</div>
	<div class="event-desc">
		<?php echo get_the_content( $post->ID ); ?>
	</div>
</div>



