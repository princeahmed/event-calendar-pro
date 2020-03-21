<?php
//todo Update message with add_option alert
$type = empty( $_REQUEST['feedback'] ) ? 'success' : esc_attr( $_REQUEST['feedback'] );
$code = empty( $_REQUEST['code'] ) ? '' : esc_attr( $_REQUEST['code'] );

if ( ! empty( $code ) ){
	echo sprintf('<div class="ecp-message %s" id="event-calendar-pro-response">%s</div>', sanitize_title( $type ), ecp_get_feedback_message( $_REQUEST['code']));
}
