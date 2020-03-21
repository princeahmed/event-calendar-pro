<?php

global $post;

$startdate = ecp_get_event_meta( $post->ID, 'startdate' );
$enddate   = ecp_get_event_meta( $post->ID, 'enddate' );
$starttime = ecp_get_event_meta( $post->ID, 'starttime' );
$endtime   = ecp_get_event_meta( $post->ID, 'endtime' );
$until     = ! empty( $endtime ) ? ' until' : '';
?>

<div class="ecp-single-date-time">
	<h3 class="title">Date/Time</h3>
	<h4>
		<?php echo $startdate ? date( 'F d, Y', strtotime( $startdate ) ) . ' to' : ''; ?>
		<?php echo $enddate ? date( 'F d, Y', strtotime( $enddate ) ) : ''; ?>
		<br>
		<?php echo $starttime ? date( 'h:i A', strtotime( $starttime ) ) : '' ?>
		<?php echo $until; ?>
		<?php echo $endtime ? date( 'h:i A', strtotime( $endtime ) ) : ''; ?>
	</h4>
</div>
