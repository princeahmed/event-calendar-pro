<?php

include WPECP_INCLUDES . '/class-ics.php';

$keyword    = ! empty( $_GET['search_keyword'] ) ? $_GET['search_keyword'] : '';
$category   = ! empty( $_GET['event_category'] ) ? sanitize_key( $_GET['event_category'] ) : '';
$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$start_date = ! empty( $_GET['start_date'] ) ? sanitize_key( $_GET['start_date'] ) : '';
$end_date   = ! empty( $_GET['end_date'] ) ? sanitize_key( $_GET['end_date'] ) : '';
$date       = ! empty( $_GET['date'] ) ? sanitize_key( $_GET['date'] ) : '';
$perpage    = 10;

$query_args = apply_filters( 'ecp_events_output_query_args', [
	'offset'     => ! empty( $paged ) ? ( $paged - 1 ) * $perpage : 0,
	'categories' => ! empty( $category ) ? $category : null,
	'search'     => ! empty( $keyword ) ? $keyword : null,
	'start_date' => $start_date,
	'end_date'   => $end_date,
	'date'       => $date,
	'number'     => $perpage,
] );

$events   = ecp_get_event_list( $query_args );

if ( $events ) {
	$ics = [];
	foreach ( $events as $post ) {
		setup_postdata( $post );
		$startdate = ecp_get_event_meta( $post->ID, 'startdate' );
		$enddate   = ecp_get_event_meta( $post->ID, 'enddate' );
		$starttime = ecp_get_event_meta( $post->ID, 'starttime' );
		$endtime   = ecp_get_event_meta( $post->ID, 'endtime' );
		$location  = ecp_get_event_meta( $post->ID, 'location' );
		$url       = ecp_get_event_meta( $post->ID, 'url' );

		$ics[] = new \WebPublisherPro\EventCalendarPro\ECP_ICS( array(
			'location'    => $location,
			'description' => esc_html( get_the_excerpt( $post ) ),
			'dtstart'     => $startdate,
			'dtend'       => $enddate,
			'summary'     => sanitize_text_field( get_the_title( $post ) ),
			'url'         => $url,
		) );

	}

	$ics_string = '';
	foreach ( $ics as $ic ) {
		$ics_string .= $ic->to_string() . "\n";
	}
}

?>

<div class="ecp-widget-event-filter event-listing">
	<form action="<?php echo ecp_get_page_url( 'events_page' ); ?>" method="get">
		<div class="form-group">
			<label for="event_category">Category:</label>
			<select name="event_category" id="event_category" class="form-control">
				<option value="">All</option>
				<?php foreach ( ecp_get_all_event_categories( 'term_id' ) as $term_id => $name ) {
					$selected = selected( $term_id, ! empty( $_REQUEST['event_category'] ) ? intval( $_REQUEST['event_category'] ) : 0, false );
					printf( '<option value="%s" %s >%s</option>', $term_id, $selected, $name );
				} ?>
			</select>
		</div>

		<div class="form-group">
			<label for="start_date"><?php _e( 'Start Date:', 'event-calendar-pro' ); ?></label>
			<input type='text' class="form-control ecp-date-calendar" name="start_date" placeholder="<?php echo date( 'Y-m-d' ) ?>" value="<?php echo $start_date; ?>"/>
		</div>
		<div class="form-group">
			<label for="end_date"><?php _e( 'End Date:', 'event-calendar-pro' ); ?></label>
			<input type='text' class="form-control ecp-date-calendar" name="end_date" placeholder="<?php echo date( 'Y-m-d' ) ?>" value="<?php echo $end_date; ?>"/>
		</div>
		<div class="form-group ecp-search-filter">
			<input type="search" name="search_keyword" placeholder="Search for." value="<?php echo ( ! empty( $_GET['search_keyword'] ) ) ? $_GET['search_keyword'] : ''; ?>">
			<button class="btn btn-default" type="submit"></button>
		</div>
	</form>
</div>

<div class="ecp-widget-event-footer event-listing clearfix">
	<a href="<?php echo ecp_get_page_url( 'event_submit_page' ); ?>" class="btn btn-default event-submit">Submit an event
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon" alt=""></a>

	<form action="" method="post">
		<?php wp_nonce_field( 'calendar_download', 'calendar_download_nonce' ) ?>
		<input type="hidden" name="action" value="calendar_download">
		<input type="hidden" name="calendar_name" value="Calendar">
		<input type="hidden" name="ics_string" value="<?php echo ( isset( $ics_string ) ) ? $ics_string : ''; ?>">
		<button type="submit" class="btn btn-default calendar-download">
			Get this Calendar
			<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon">
		</button>
	</form>
</div>
