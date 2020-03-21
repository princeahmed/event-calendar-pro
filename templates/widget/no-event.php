<?php
$date = get_query_var( 'date' );
?>
<div class="ecp-widget-event-list">
	<h4><?php echo ! empty( $date ) ? date( 'D, M j, Y', strtotime( $date ) ) : date( 'D, M j, Y' ); ?></h4>
	<h4 class="text-center">No Events Found</h4>
</div>

