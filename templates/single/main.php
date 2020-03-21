<?php
global $post;

ecp_get_template( 'single/start.php' );
ecp_get_template( 'single/date-time.php' );
echo ecp_get_recurrence_message( $post->ID );
ecp_get_template( 'single/content.php' );
ecp_get_template( 'single/location.php' );
ecp_get_template( 'single/information.php' );
ecp_get_template( 'single/end.php' );
