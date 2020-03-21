<?php

namespace WebPublisherPro\EventCalendarPro;

use WebPublisherPro\EventCalendarPro\Admin\MetaBox;

class FormHandler {
	/**
	 * FormHandler constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_ecp-user-registration', array( $this, 'handle_user_registration_form' ) );
		add_action( 'admin_post_nopriv_ecp-user-registration', array( $this, 'handle_user_registration_form' ) );
		add_action( 'admin_post_ecp_submit_event_form', [ $this, 'handle_event_submit_form' ] );
		add_action( 'init', [ $this, 'calendar_download' ] );
		add_action( 'admin_init', [ $this, 'event_state_country' ] );
		add_action( 'admin_init', [ $this, 'export_events' ] );
	}

	function handle_user_registration_form() {

		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'ecp-user-registration' ) ) {
			wp_die( 'No Cheating' );
		}

		$url = untrailingslashit( site_url( '/' ) ) . $_REQUEST['_wp_http_referer'];

		do_action( 'ecp_before_user_registration', $url );

		if ( ! apply_filters( 'ecp_disable_math_captcha', false ) ) {
			if ( ( empty( $_REQUEST['math'] ) || ( md5( $_REQUEST['captcha'] . 'secret-code' ) != $_REQUEST['math'] ) ) ) {
				$this->redirect_with_message( $url, 'invalid_captcha', 'error' );
			}
		}

		$email      = ! empty( $_REQUEST['email'] ) ? sanitize_email( $_REQUEST['email'] ) : '';
		$title      = ! empty( $_REQUEST['title'] ) ? sanitize_key( $_REQUEST['title'] ) : '';
		$firstname  = ! empty( $_REQUEST['firstname'] ) ? sanitize_key( $_REQUEST['firstname'] ) : '';
		$lastname   = ! empty( $_REQUEST['lastname'] ) ? sanitize_key( $_REQUEST['lastname'] ) : '';
		$password   = ! empty( $_REQUEST['password'] ) ? $_REQUEST['password'] : '';
		$screenname = ! empty( $_REQUEST['screenname'] ) ? sanitize_key( $_REQUEST['screenname'] ) : '';
		$company    = ! empty( $_REQUEST['company'] ) ? sanitize_text_field( $_REQUEST['company'] ) : '';
		$phone      = ! empty( $_REQUEST['phone'] ) ? sanitize_text_field( $_REQUEST['phone'] ) : '';
		$address    = ! empty( $_REQUEST['address'] ) ? sanitize_text_field( $_REQUEST['address'] ) : '';
		$city       = ! empty( $_REQUEST['city'] ) ? sanitize_key( $_REQUEST['city'] ) : '';
		$zipcode    = ! empty( $_REQUEST['zipcode'] ) ? sanitize_key( $_REQUEST['zipcode'] ) : '';
		$state      = ! empty( $_REQUEST['state'] ) ? sanitize_key( $_REQUEST['state'] ) : '';
		$country    = ! empty( $_REQUEST['country'] ) ? sanitize_key( $_REQUEST['country'] ) : '';
		$optin      = ! empty( $_REQUEST['optin'] ) ? sanitize_key( $_REQUEST['optin'] ) : '';


		if ( empty( $_REQUEST['email'] ) || ! is_email( $email ) ) {
			$this->redirect_with_message( $url, 'invalid_email', 'error' );
		} elseif ( empty( $password ) ) {
			$this->redirect_with_message( $url, 'empty_password', 'error' );
		}

		if ( email_exists( $email ) ) {
			$this->redirect_with_message( $url, 'email_exists', 'error' );
		}

		$user_id = wp_insert_user(
			array(
				'user_login'    => $email,
				'user_pass'     => $password,
				'user_nicename' => $screenname,
				'user_email'    => $email,
				'display_name'  => $screenname,
			)
		);

		update_user_meta( $user_id, 'title', $title );
		update_user_meta( $user_id, 'first_name', $firstname );
		update_user_meta( $user_id, 'last_name', $lastname );
		update_user_meta( $user_id, 'zipcode', $zipcode );
		update_user_meta( $user_id, 'address', $address );
		update_user_meta( $user_id, 'city', $city );
		update_user_meta( $user_id, 'state', $state );
		update_user_meta( $user_id, 'country', $country );
		update_user_meta( $user_id, 'phone', $phone );
		update_user_meta( $user_id, 'company', $company );
		update_user_meta( $user_id, 'optin', $optin );

		if ( email_exists( $email ) ) {
			$this->redirect_with_message( apply_filters( 'ecp_user_redirect_url', $url, $user_id ), 'successfull_rgistration', 'success' );
		}
	}

	/**
	 * Redirect the user with custom message
	 */

	public function redirect_with_message( $url, $code, $type = 'success', $args = array() ) {
		$redirect_url = add_query_arg( wp_parse_args( $args, array(
			'feedback' => $type,
			'code'     => $code,
		) ), $url );
		wp_redirect( $redirect_url );
		exit();
	}

	/**
	 * The Event Submit Form from FrontEnd
	 */
	public function handle_event_submit_form() {

		if ( ! isset( $_POST['ecp_submit_event_form_nonce'] ) || ! wp_verify_nonce( $_POST['ecp_submit_event_form_nonce'], 'ecp_submit_event_form' ) ) {
			wp_die( 'No Cheating' );
		}

		$redirect_url = untrailingslashit( site_url( '/' ) ) . $_REQUEST['_wp_http_referer'];

		do_action( 'ecp_before_event_submit', $redirect_url );

		if ( ! apply_filters( 'ecp_disable_math_captcha', false ) ) {
			$is_valid_captcha = empty( $_REQUEST['math'] ) || ( md5( $_REQUEST['captcha'] . 'secret-code' ) != $_REQUEST['math'] ) ? false : true;
			if ( false == apply_filters( 'ecp_captcha_check', $is_valid_captcha ) ) {
				$this->redirect_with_message( $redirect_url, 'invalid_captcha', 'error' );
			}
		}

		//Post fields
		$event_title       = ! empty( $_POST['ecp_event_title'] ) ? sanitize_text_field( $_POST['ecp_event_title'] ) : '';
		$event_description = ! empty( $_POST['ecp_event_desc'] ) ? wp_kses_post( $_POST['ecp_event_desc'] ) : '';
		$event_categories  = ! empty( $_POST['ecp_event_category'] ) ? array_map( 'absint', $_POST['ecp_event_category'] ) : '';
		$thumbnail_id      = ! empty( $_POST['_thumbnail_id'] ) ? intval( $_POST['_thumbnail_id'] ) : '';

		$event_id = ! empty( $_REQUEST['event_id'] ) ? intval( $_REQUEST['event_id'] ) : '';

		if ( $event_id ) {

			$post_id = wp_update_post( array(
				'ID'           => $event_id,
				'post_title'   => $event_title,
				'post_name'    => $event_title,
				'post_content' => $event_description,
				'post_type'    => 'pro_event',
			) );
		} else {

			$post_id = wp_insert_post( array(
				'post_title'   => $event_title,
				'post_name'    => $event_title,
				'post_content' => $event_description,
				'post_status'  => apply_filters( 'ecp_user_submitted_status', 'draft' ),
				'post_type'    => 'pro_event',
			) );
		}

		//event submit email notification
		do_action( 'ecp_event_submit_email_notification', $post_id );

		$metabox = new MetaBox();
		$metabox->save_event_meta( $post_id );

		if ( ! empty( $_FILES['ecp_event_image'] ) && empty( $_FILES['ecp_event_image']['error'] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile     = $_FILES['ecp_event_image'];
			$upload_overrides = array( 'test_form' => false );
			$movefile         = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile ) {

				$event_image = $movefile['file'];

				$upload_id = wp_insert_attachment( array(
					'guid'           => $movefile['url'],
					'post_mime_type' => $movefile['type'],
					'post_content'   => '',
					'post_status'    => 'inherit'
				), $event_image, 0 );

				$attach_data = wp_generate_attachment_metadata( $upload_id, $movefile['file'] );
				wp_update_attachment_metadata( $upload_id, $attach_data );

				$thumbnail_id = $upload_id;


			}
		}

		if ( ! empty( $thumbnail_id ) ) {
			update_post_meta( $post_id, '_thumbnail_id', $thumbnail_id );
		}

		update_post_meta( $post_id, 'readersubmitted', 'yes' );


		if ( ! empty( $event_categories ) ) {

			$cats = implode( ',', $event_categories );

			wp_set_post_terms( $post_id, $cats, 'event_category', false );
		}

		do_action( 'ecp_event_submitted', $post_id );

		$this->redirect_with_message( $redirect_url, 'event_submitted', 'success' );

		exit();

	}

	/**
	 * Download The Events Calendar
	 */
	function calendar_download() {
		if ( empty( $_POST['action'] ) || $_POST['action'] != 'calendar_download' ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['calendar_download_nonce'], 'calendar_download' ) ) {
			wp_die( 'No cheating' );
		}

		$filename = $_POST['calendar_name'] . '.ics';
		header( 'Content-Type: text/calendar; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		echo $_POST['ics_string'];
		die();
	}

	/**
	 * Add events state and country
	 */

	function event_state_country() {

		if ( ! isset( $_POST['option_page'] ) ) {
			return;
		}

		if ( empty( $_POST['option_page'] ) || $_POST['option_page'] != 'ecp_countries' and $_POST['option_page'] != 'ecp_states' ) {
			return;
		}

		if ( $_POST['option_page'] == 'ecp_countries' ) {
			$countries = unserialize( get_option( 'countries' ) );
			if ( isset( $_POST['delete'] ) ) {
				$country_code = $_POST['delete'];
				unset( $countries[ $country_code ] );
				update_option( 'countries', serialize( $countries ) );

				return;
			}
			$country_name               = $_POST['country'];
			$country_code               = $_POST['country_code'];
			$countries[ $country_code ] = $country_name;
			update_option( 'countries', serialize( $countries ) );

		} elseif ( $_POST['option_page'] == 'ecp_states' ) {
			$states = unserialize( get_option( 'states' ) );
			if ( isset( $_POST['delete'] ) ) {
				$state_code = $_POST['delete'];
				unset( $states[ $state_code ] );
				update_option( 'states', serialize( $states ) );

				return;
			}
			$state_name            = $_POST['state_name'];
			$state_code            = $_POST['state_code'];
			$country_code          = $_POST['country_code'];
			$states[ $state_code ] = [
				'state'        => $state_name,
				'country_code' => $country_code
			];
			update_option( 'states', serialize( $states ) );
		}
	}

	function export_events() {


		if ( empty( $_REQUEST['post_type'] ) || 'pro_event' != $_REQUEST['post_type'] || empty( $_REQUEST['action'] ) || 'export' != $_REQUEST['action'] ) {
			return;
		}

		$events = get_posts( array(
			'post_type'   => 'pro_event',
			'numberposts' => - 1,
			'post__in'    => $_REQUEST['post'],
			'post_status' => 'any',
		) );


		foreach ( $events as $event ) {
			$row = [
				'id'               => $event->ID,
				'title'            => $event->post_title,
				'content'          => $event->post_content,
				'url'              => get_permalink( $event ),
				'image'            => get_the_post_thumbnail_url( $event, 'large' ),
				'startdate'        => $event->start_date,
				'enddate'          => $event->end_date,
				'starttime'        => $event->start_time,
				'endtime'          => $event->end_time,
				'starthour'        => get_post_meta( $event->ID, 'starthour', true ),
				'endhour'          => get_post_meta( $event->ID, 'endhour', true ),
				'location'         => get_post_meta( $event->ID, 'location', true ),
				'address'          => get_post_meta( $event->ID, 'address', true ),
				'address2'         => get_post_meta( $event->ID, 'address2', true ),
				'city'             => get_post_meta( $event->ID, 'city', true ),
				'state'            => get_post_meta( $event->ID, 'state', true ),
				'zip'              => get_post_meta( $event->ID, 'zip', true ),
				'country'          => get_post_meta( $event->ID, 'country', true ),
				'country_name'     => get_post_meta( $event->ID, 'country_name', true ),
				'name'             => get_post_meta( $event->ID, 'name', true ),
				'phone'            => get_post_meta( $event->ID, 'phone', true ),
				'contactname'      => get_post_meta( $event->ID, 'contactname', true ),
				'contactmail'      => get_post_meta( $event->ID, 'contactmail', true ),
				'website'          => get_post_meta( $event->ID, 'url', true ),
				'cost'             => get_post_meta( $event->ID, 'cost', true ),
				'price'            => get_post_meta( $event->ID, 'price', true ),
				'age'              => get_post_meta( $event->ID, 'age', true ),
				'age1'             => get_post_meta( $event->ID, 'age1', true ),
				'age_new'          => get_post_meta( $event->ID, 'age_new', true ),
				'time'             => get_post_meta( $event->ID, 'time', true ),
				'eventdate'        => get_post_meta( $event->ID, 'eventdate', true ),
				'listingtype'      => get_post_meta( $event->ID, 'listingtype', true ),
				'featured'         => get_post_meta( $event->ID, 'featured', true ),
				'sponsor'          => get_post_meta( $event->ID, 'sponsor', true ),
				'eventtype'        => get_post_meta( $event->ID, 'eventtype', true ),
				'readersubmitted'  => get_post_meta( $event->ID, 'readersubmitted', true ),
				'photodescription' => get_post_meta( $event->ID, 'photodescription', true ),
				'heading'          => get_post_meta( $event->ID, 'heading', true ),
				'dailytype'        => get_post_meta( $event->ID, 'dailytype', true ),
				'daily_ndays'      => get_post_meta( $event->ID, 'daily_ndays', true ),
				'weekly_nweeks'    => get_post_meta( $event->ID, 'weekly_nweeks', true ),
				'monthly_dom'      => get_post_meta( $event->ID, 'monthly_dom', true ),
				'monthly_nmonths'  => get_post_meta( $event->ID, 'monthly_nmonths', true ),
				'recuryearly'      => get_post_meta( $event->ID, 'recuryearly', true ),
				'yearly_day'       => get_post_meta( $event->ID, 'dailytype', true ),
				'yearly_dommonth'  => get_post_meta( $event->ID, 'yearly_dommonth', true ),
				'yearly_interval'  => get_post_meta( $event->ID, 'yearly_interval', true ),
				'yearly_weekday'   => get_post_meta( $event->ID, 'yearly_weekday', true ),
				'yearly_doymonth'  => get_post_meta( $event->ID, 'monthly_nmonths', true ),
				'weekly_weekdays'  => get_post_meta( $event->ID, 'weekly_weekdays', true ),
				'recurtype'        => get_post_meta( $event->ID, 'recurtype', true ),
				'recurmonthly'     => get_post_meta( $event->ID, 'recurmonthly', true ),
				'monthly_interval' => get_post_meta( $event->ID, 'monthly_interval', true ),
				'monthly_weekdays' => get_post_meta( $event->ID, 'monthly_weekdays', true ),
				'monthly_months'   => get_post_meta( $event->ID, 'monthly_months', true ),
				'weekdays'         => get_post_meta( $event->ID, 'weekdays', true ),
				'month'            => get_post_meta( $event->ID, 'month', true ),
			];

			$records[] = $row;
		}


		$header = array_keys( $records[0] );

		$file_name = "export-" . 'events' . '-' . date( 'd-m-Y-h-i-s' ) . ".csv";
		header( "Content-type: application/csv" );
		header( "Content-Disposition: attachment; filename=$file_name" );
		$fp = fopen( 'php://output', 'w' );
		fputcsv( $fp, $header );
		foreach ( $records as $record ) {
			fputcsv( $fp, array_values( (array) $record ) );
		}

		fclose( $fp );

		exit();
	}

}
