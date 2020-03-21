<?php

$location = ecp_get_event_location( $event->ID );
$location = ecp_get_event_meta( $event->ID, 'location' );
$address  = ecp_get_event_meta( $event->ID, 'address' );
$address2 = ecp_get_event_meta( $event->ID, 'address2' );
$city     = ecp_get_event_meta( $event->ID, 'city' );
$state    = ecp_get_event_meta( $event->ID, 'state' );
$country  = ecp_get_event_meta( $event->ID, 'country' );
$zip      = ecp_get_event_meta( $event->ID, 'zip' );
$start_time = ecp_get_event_meta( $event->ID, 'starttime' );
$end_time   = ecp_get_event_meta( $event->ID, 'endtime' );

?>
<li class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#ecp-post-<?php echo $event->ID; ?>">
				<?php if ( ! empty( $start_time ) ) { ?>
					<span class="event-time"><?php echo $start_time . ' - ' . $end_time; ?></span> |
				<?php } ?>
				<span class="event-title"><?php echo wp_trim_words( get_the_title( $event->ID ), 15 ); ?></span>
				<span class="pull-right"><i class="caret"></i></span> </a>
		</h4>
	</div>
	<div class="panel-collapse collapse" id="ecp-post-<?php echo $event->ID; ?>">

		<?php echo wp_trim_words( $event->post_content , 70 ); ?>

		<?php if ( ! empty( $location ) ) { ?>
			<div class="list-address">
				<p class="ecp-widget-event-list-section">Where</p>
				<?php echo $location ? "<span class='location'>$location</span>" : ''; ?>

				<a href="http://maps.google.com/maps?q=<?php echo urlencode( ecp_get_full_address( $event->ID ) ); ?>"
				   class="map-btn pull-right ecp-widget-event-list-section" target="_blank">View map</a>
				<br>
				<address>
					<?php echo $address ? "<span class='address'>$address, </span>" : '' ?> <?php echo $address2 ? "<span class='address2'>$address2</span>" : '' ?>
					<br>
					<?php echo $city ? "<span class='city'>$city,</span>" : '' ?> <?php echo $zip ? "<span class='zipcode'>$zip,</span>" : '' ?> <?php echo $state ? "<span class='state'>$state</span>" : '' ?><?php echo $country ? ", <span class='country'>$country</span>" : '' ?>
				</address>
			</div>
		<?php } ?>

		<p class="more-link ecp-widget-event-list-section">
			<button class="button"><a href="<?php echo get_the_permalink( $event->ID ); ?>">More Information</a>
			</button>
		</p>

	</div>
</li>


