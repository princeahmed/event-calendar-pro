<div class="ecp-widget-event-filter">
	<form action="<?php echo ecp_get_page_url( 'events_page' ); ?>" method="get">

		<div class="form-group">
			<input type="search" name="search_keyword" placeholder="search" value="<?php echo ( ! empty( $_GET['search_keyword'] ) ) ? $_GET['search_keyword'] : ''; ?>">
			<button type="submit"></button>
		</div>

		<div class="form-group">
			<label for="event_category">Category:</label> <select name="event_category" id="event_category">
				<option value="">All</option>
				<?php foreach ( ecp_get_all_event_categories( 'term_id' ) as $term_id => $name ) {
					$selected = selected( $term_id, ! empty( $_REQUEST['event_category'] ) ? intval( $_REQUEST['event_category'] ) : 0, false );
					printf( '<option value="%s" %s >%s</option>', $term_id, $selected, $name );
				} ?>
			</select>
		</div>

	</form>
</div>
<div class="ecp-widget-event-footer clearfix">
	<a href="<?php echo ecp_get_page_url( 'event_submit_page' ); ?>" class="btn btn-default submit-event">Submit an event
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon" alt=""></a>
	<a href="<?php echo ecp_get_page_url( 'events_page' ); ?>" class="btn btn-default view-event">View all listings
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/right-arrow.svg' ?>" class="ecp-icon" alt=""></a>
</div>
