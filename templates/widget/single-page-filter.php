<?php

include WPECP_INCLUDES . '/class-ics.php';
global $post;

$title       = sanitize_text_field( get_the_title( $post->ID ) );
$description = esc_html( get_the_excerpt( $post ) );
$startdate   = ecp_get_event_meta( $post->ID, 'startdate' );
$enddate     = ecp_get_event_meta( $post->ID, 'enddate' );
$starttime   = ecp_get_event_meta( $post->ID, 'starttime' );
$endtime     = ecp_get_event_meta( $post->ID, 'endtime' );
$location    = ecp_get_event_meta( $post->ID, 'location' );
$url         = ecp_get_event_meta( $post->ID, 'url' );

$ics = new \WebPublisherPro\EventCalendarPro\ECP_ICS( array(
	'description' => $description,
	'dtstart'     => $startdate,
	'location'    => $location,
	'dtend'       => $enddate,
	'summary'     => $title,
	'url'         => $url,
) );

$ics_string = $ics->to_string() . "\n";

?>

<div class="ecp-widget-event-footer single-listing">
	<a href="<?php echo ecp_get_page_url( 'events_page' ); ?>" class="btn btn-default"> Calendar Home
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon"> </a>

	<a href="<?php echo ecp_get_page_url( 'event_submit_page' ); ?>" class="btn btn-default">Submit an event
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon"></a>

	<form action="" method="post">
		<?php wp_nonce_field( 'calendar_download', 'calendar_download_nonce' ) ?>
		<input type="hidden" name="action" value="calendar_download">
		<input type="hidden" name="calendar_name" value="<?php echo $title; ?>">
		<input type="hidden" name="ics_string" value="<?php echo $ics_string; ?>">
		<button type="submit" class="btn btn-block btn-default">
			Get this Event <img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon">
		</button>
	</form>

</div>
