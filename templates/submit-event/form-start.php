<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( 'ecp_submit_event_form', 'ecp_submit_event_form_nonce' ); ?>
	<input type="hidden" name="action" value="ecp_submit_event_form">
	<?php apply_filters('ecp_submit_event_start', '') ?>
