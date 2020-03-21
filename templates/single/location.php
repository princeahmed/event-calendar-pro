<?php

global $post;

$location = ecp_get_event_meta( $post->ID, 'location' );
$address  = ecp_get_event_meta( $post->ID, 'address' );
$address2 = ecp_get_event_meta( $post->ID, 'address2' );
$city     = ecp_get_event_meta( $post->ID, 'city' );
$state    = ecp_get_event_meta( $post->ID, 'state' );
$country  = ecp_get_event_meta( $post->ID, 'country' );
$zip      = ecp_get_event_meta( $post->ID, 'zip' );

if ( ! empty( $location ) ) { ?>
	<div class="ecp-single-event-location">
		<h3 class="title">Location</h3>
		<?php echo "<span class='location'>$location</span>" ?>
		<a href="http://maps.google.com/maps?q=<?php echo urlencode( ecp_get_full_address( $post->ID ) ); ?>" class="map-btn pull-right" target="_blank">View map</a><br>
		<address>
			<?php echo $address ? "<span class='address'>$address, </span>" : '' ?>
			<?php echo $address2 ? "<span class='address2'>$address2</span>" : '' ?>
			<br>
			<?php echo $city ? "<span class='city'>$city,</span>" : '' ?>
			<?php echo $zip ? "<span class='zipcode'>$zip,</span>" : '' ?> <?php echo $state ? "<span class='state'>$state</span>" : '' ?>
			<?php echo $country ? ", <span class='country'>$country</span>" : '' ?>
		</address>
	</div>
<?php } ?>
