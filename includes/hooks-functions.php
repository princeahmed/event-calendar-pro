<?php

function ecp_send_email_on_event_submit( $event_id ) {
	$to         = ecp_get_settings( 'event_notification_email_address', get_option( 'admin_email' ), 'event_calendar_pro_page_settings' );
	$subject    = apply_filters( 'ecp_event_email_subject', 'New Event Submitted to ' . get_bloginfo( 'name' ) );
	$location   = ecp_get_event_meta( $event_id, 'location' );
	$start_date = ecp_get_event_meta( $event_id, 'startdate' );
	$end_date   = ecp_get_event_meta( $event_id, 'enddate' );
	ob_start();
	?>

	<div class="email-wrap">
		<h3>New Event Submitted: <?php echo get_the_title( $event_id ) ?></h3>
		<strong>Event Details</strong>
		<ul>
			<li>Event Location:<?php echo $location; ?></li>
			<li>Event Start Date:<?php echo $start_date; ?></li>
			<li>Event End Date:<?php echo $end_date; ?></li>
		</ul>
		<p>
			<a href="<?php echo get_the_permalink( $event_id ) ?>">View Event</a>
		</p>
	</div>

	<?php $message = ob_get_clean();

	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $to, $subject, $message, $headers );
}

add_action( 'ecp_event_submit_email_notification', 'ecp_send_email_on_event_submit' );

function redirect_to_user_registration_page() {

	$submit_page       = ecp_get_settings( 'event_submit_page', '', 'event_calendar_pro_page_settings' );
	$registration_page = ecp_get_settings( 'user_registration_page', '', 'event_calendar_pro_page_settings' );

	if ( ! empty( $submit_page ) && is_page( $submit_page ) && ! is_user_logged_in() ) {
		$registration_page_url = ecp_get_page_url( 'user_registration_page' );
		wp_safe_redirect( $registration_page_url );
		exit();
	}

	if ( ! empty( $registration_page ) && is_page( $registration_page ) && is_user_logged_in() ) {
		$submit_page_url = ecp_get_page_url( 'event_submit_page' );
		wp_safe_redirect( $submit_page_url );
		exit();
	}


}

add_action( 'template_redirect', 'redirect_to_user_registration_page' );

function ecp_add_export_action( $content ) {
	unset( $content['trash'] );
	$content['export'] = __( 'Export', 'event-calendar-pro' );
	$content['trash']  = __( 'Move to Trash', 'event-calendar-pro' );
	return $content;
}

add_filter( 'bulk_actions-edit-pro_event', 'ecp_add_export_action' );

function ecp_add_event_schema() {
	if ( ! is_singular( 'pro_event' ) ) {
		return;
	}
	global $post;

	$startdate   = apply_filters( 'ecp_event_schema_startdate_value', ecp_get_event_meta( $post->ID, 'startdate' ), $post );
	$enddate     = apply_filters( 'ecp_event_schema_enddate_value', ecp_get_event_meta( $post->ID, 'enddate' ), $post );
	$starttime   = apply_filters( 'ecp_event_schema_starttime_value', ecp_get_event_meta( $post->ID, 'starttime' ), $post );
	$endtime     = apply_filters( 'ecp_event_schema_endtime_value', ecp_get_event_meta( $post->ID, 'endtime' ), $post );
	$location    = apply_filters( 'ecp_event_schema_location_value', ecp_get_event_meta( $post->ID, 'location' ), $post );
	$address     = apply_filters( 'ecp_event_schema_address_value', ecp_get_event_meta( $post->ID, 'address' ), $post );
	$address2    = apply_filters( 'ecp_event_schema_address2_value', ecp_get_event_meta( $post->ID, 'address2' ), $post );
	$city        = apply_filters( 'ecp_event_schema_city_value', ecp_get_event_meta( $post->ID, 'city' ), $post );
	$state       = apply_filters( 'ecp_event_schema_state_value', ecp_get_event_meta( $post->ID, 'state' ), $post );
	$country     = apply_filters( 'ecp_event_schema_country_value', ecp_get_event_meta( $post->ID, 'country' ), $post );
	$zip         = apply_filters( 'ecp_event_schema_zip_value', ecp_get_event_meta( $post->ID, 'zip' ), $post );
	$url         = apply_filters( 'ecp_event_schema_url_value', ecp_get_event_meta($post->ID, 'url'), $post );
	$name        = apply_filters( 'ecp_event_schema_sponsor_name_value', ecp_get_event_meta($post->ID, 'name'), $post );
	$phone       = apply_filters( 'ecp_event_schema_sponsor_phone_value', ecp_get_event_meta($post->ID, 'phone'), $post );
	$contactmail = apply_filters( 'ecp_event_schema_sponsor_contactmail_value', ecp_get_event_meta($post->ID, 'contactmail'), $post );
	$cost        = apply_filters( 'ecp_event_schema_cost_value', ecp_get_event_meta($post->ID, 'cost'), $post );

	$image = get_the_post_thumbnail_url( $post );

	$description = wp_trim_words( $post->post_content, '30', '' );

	$schema_data = array(
		"@context" => "https://schema.org",
		"@type" => "Event",
		"name" => htmlentities( $post->post_title ),
		"startDate" => $startdate . " " . $starttime,
		"endDate" => $enddate . " " . $endtime,
		"description" => htmlentities( $description ),
		"location" => array(
			"@type" => "Place",
			"name" => $location,
			"address" => array(
				"@type" => "PostalAddress",
			),
		),
	);

	if ( ! empty( $image ) ) {
		$schema_data['image'][] = $image;
	}

	if ( ! empty( $url ) ) {
		$schema_data['url'] = $url;
	}

	$street = array();

	if ( ! empty( $address ) ) {
		$street[] = $address;
	}
	if ( ! empty( $address2 ) ) {
		$street[] = $address2;
	}
	$street = implode( ', ', $street );
	if ( ! empty( $street ) ) {
		$schema_data['location']['address']['streetAddress'] = $street;
	}

	if ( ! empty( $city ) ) {
		$schema_data['location']['address']['addressLocality'] = $city;
	}

	if ( ! empty( $state ) ) {
		$schema_data['location']['address']['addressRegion'] = $state;
	}

	if ( ! empty( $country ) ) {
		$schema_data['location']['address']['addressCountry'] = $country;
	}

	if ( ! empty( $zip ) ) {
		$schema_data['location']['address']['postalCode'] = $zip;
	}

	if ( ! empty( $name ) ) {
		$schema_data['sponsor'] = array(
			"@type" => "Organization",
			"name"  => $name,
		);

		if ( ! empty( $phone ) ) {
			$schema_data['sponsor']['telephone'] = $phone;
		}
		if ( ! empty( $contactmail ) ) {
			$schema_data['sponsor']['email'] = $contactmail;
		}
	}

	if ( ! empty( $cost ) ) {
		$schema_data['offers'] = array(
			"@type" => "Offer",
			"availability" => "https://schema.org/InStock",
			"price" => $cost,
		);
	}

	?>
	<script type="application/ld+json"><?php echo json_encode( $schema_data ); ?></script>
	<?php
}
add_action( 'wp_head', 'ecp_add_event_schema' );
