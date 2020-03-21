<?php

if ( is_page( $events_page ) ) {
	ecp_get_template( 'widget/lists-page-filter.php' );
} elseif ( is_singular( 'pro_event' ) ) {
	ecp_get_template( 'widget/single-page-filter.php' );
} else {
	ecp_get_template( 'widget/calendar.php' );
	if ( $events ) {
		ecp_get_template( 'widget/list-start.php' );
		foreach ( $events as $event ) {
			ecp_get_template( 'widget/list.php', [ 'event' => $event ] );
		}
		ecp_get_template( 'widget/list-end.php' );
	} else {
		ecp_get_template( 'widget/no-event.php' );
	}
	ecp_get_template( 'widget/filter.php' );
}
?>
