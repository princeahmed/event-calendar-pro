<?php

namespace WebPublisherPro\EventCalendarPro\Admin;

class MetaBox {
	/**
	 * MetaBox constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes_pro_event', array( $this, 'register_event_metabox' ) );
		add_action( 'save_post_pro_event', array( $this, 'save_event_meta' ) );
		add_action( 'post_edit_form_tag', function () {
			echo ' enctype="multipart/form-data"';
		} );
	}

	public function register_event_metabox() {
		add_meta_box( 'event-date-time', __( 'Date/Time', 'event-calendar-pro' ), array(
			$this,
			'render_event_date_time_metabox'
		) );

		add_meta_box( 'event-recurrence', __( 'Event Recurrence', 'event-calendar-pro' ), array(
			$this,
			'render_event_recurrence_metabox'
		) );

		add_meta_box( 'event-location', __( 'Location', 'event-calendar-pro' ), array(
			$this,
			'render_event_location_metabox'
		) );

		add_meta_box( 'event-sponsor', __( 'Sponsor', 'event-calendar-pro' ), array(
			$this,
			'render_event_sponsor_metabox'
		) );

		add_meta_box( 'event-info', __( 'Additional Information', 'event-calendar-pro' ), array(
			$this,
			'render_event_info_metabox'
		) );


		$remove_meta_boxes = apply_filters( 'ecp_remove_meta_boxes', false );

		if ( ! empty( $remove_meta_boxes ) ) {
			foreach ( $remove_meta_boxes as $meta_box ) {
				remove_meta_box( $meta_box, 'pro_event', 'advanced' );
			}
		}

	}

	public function render_event_date_time_metabox() {
		ecp_get_template( 'metabox/date-time.php' );
	}

	public function render_event_recurrence_metabox() {
		ecp_get_template( 'metabox/recurrence.php' );
	}

	public function render_event_location_metabox() {
		ecp_get_template( 'metabox/location.php' );
	}

	public function render_event_sponsor_metabox() {
		ecp_get_template( 'metabox/sponsor.php' );
	}

	public function render_event_info_metabox() {
		ecp_get_template( 'metabox/information.php' );
	}

	/**
	 * Save event meta data
	 *
	 * @param $post_id
	 */

	public function save_event_meta( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		$posted = empty( $_REQUEST ) ? [] : $_REQUEST;


		//date time
		$startdate = ! empty( $posted['startdate'] ) ? sanitize_key( $posted['startdate'] ) : '';
		$enddate   = ! empty( $posted['enddate'] ) ? sanitize_text_field( $posted['enddate'] ) : '';
		$starttime = ! empty( $posted['starttime'] ) ? sanitize_text_field( $posted['starttime'] ) : '';
		$endtime   = ! empty( $posted['endtime'] ) ? sanitize_text_field( $posted['endtime'] ) : '';

		//Location Address
		$countries    = unserialize( get_option( 'countries' ) );
		$location     = ! empty( $posted['location'] ) ? sanitize_text_field( $posted['location'] ) : '';
		$address      = ! empty( $posted['address'] ) ? sanitize_text_field( $posted['address'] ) : '';
		$address2     = ! empty( $posted['address2'] ) ? sanitize_text_field( $posted['address2'] ) : '';
		$city         = ! empty( $posted['city'] ) ? sanitize_text_field( $posted['city'] ) : '';
		$state        = ! empty( $posted['state'] ) ? sanitize_text_field( $posted['state'] ) : '';
		$country      = ! empty( $posted['country'] ) ? sanitize_text_field( $posted['country'] ) : '';
		$country_name = ! empty( $posted['country'] ) ? sanitize_text_field( $countries[ $country ] ) : '';
		$zip          = ! empty( $posted['zip'] ) ? sanitize_text_field( $posted['zip'] ) : '';

		//sponsor
		$name        = ! empty( $posted['name'] ) ? sanitize_text_field( $posted['name'] ) : '';
		$phone       = ! empty( $posted['phone'] ) ? sanitize_text_field( $posted['phone'] ) : '';
		$contactname = ! empty( $posted['contactname'] ) ? sanitize_text_field( $posted['contactname'] ) : '';
		$contactmail = ! empty( $posted['contactmail'] ) ? sanitize_email( $posted['contactmail'] ) : '';
		$url         = ! empty( $posted['url'] ) ? esc_url( $posted['url'] ) : '';

		//Recurrence
		$eventtype        = ! empty( $posted['eventtype'] ) ? sanitize_text_field( $posted['eventtype'] ) : '';
		$recurtype        = ! empty( $posted['recurtype'] ) ? sanitize_text_field( $posted['recurtype'] ) : '';
		$dailytype        = ! empty( $posted['dailytype'] ) ? sanitize_text_field( $posted['dailytype'] ) : '';
		$daily_ndays      = ! empty( $posted['daily_ndays'] ) ? sanitize_text_field( $posted['daily_ndays'] ) : '';
		$weekly_nweeks    = ! empty( $posted['weekly_nweeks'] ) ? sanitize_text_field( $posted['weekly_nweeks'] ) : '';
		$weekly_weekdays  = ! empty( $posted['weekly_weekdays'] ) ? join( ',', $posted['weekly_weekdays'] ) : '';
		$recurmonthly     = ! empty( $posted['recurmonthly'] ) ? sanitize_text_field( $posted['recurmonthly'] ) : '';
		$monthly_dom      = ! empty( $posted['monthly_dom'] ) ? sanitize_text_field( $posted['monthly_dom'] ) : '';
		$monthly_nmonths  = ! empty( $posted['monthly_nmonths'] ) ? sanitize_text_field( $posted['monthly_nmonths'] ) : '';
		$monthly_interval = ! empty( $posted['monthly_interval'] ) ? join( ',', $posted['monthly_interval'] ) : '';
		$monthly_weekdays = ! empty( $posted['monthly_weekdays'] ) ? join( ',', $posted['monthly_weekdays'] ) : '';
		$monthly_months   = ! empty( $posted['monthly_months'] ) ? join( ',', $posted['monthly_months'] ) : '';
		$recuryearly      = ! empty( $posted['recuryearly'] ) ? sanitize_text_field( $posted['recuryearly'] ) : '';
		$yearly_day       = ! empty( $posted['yearly_day'] ) ? sanitize_text_field( $posted['yearly_day'] ) : '';
		$yearly_dommonth  = ! empty( $posted['yearly_dommonth'] ) ? sanitize_text_field( $posted['yearly_dommonth'] ) : '';
		$yearly_interval  = ! empty( $posted['yearly_interval'] ) ? sanitize_text_field( $posted['yearly_interval'] ) : '';
		$yearly_weekday   = ! empty( $posted['yearly_weekday'] ) ? sanitize_text_field( $posted['yearly_weekday'] ) : '';
		$yearly_daymonth  = ! empty( $posted['yearly_doymonth'] ) ? sanitize_text_field( $posted['yearly_doymonth'] ) : '';

		//additional infromation
		$cost             = ! empty( $posted['cost'] ) ? sanitize_text_field( $posted['cost'] ) : '';
		$age              = ! empty( $posted['age'] ) ? sanitize_text_field( $posted['age'] ) : '';
		$featured         = ! empty( $posted['featured'] ) ? sanitize_text_field( $posted['featured'] ) : '';
		$readersubmitted  = ! empty( $posted['readersubmitted'] ) ? sanitize_text_field( $posted['readersubmitted'] ) : '';
		$photodescription = ! empty( $posted['photodescription'] ) ? sanitize_text_field( $posted['photodescription'] ) : '';

		update_post_meta( $post_id, 'startdate', $startdate );
		update_post_meta( $post_id, 'enddate', $enddate );
		update_post_meta( $post_id, 'starttime', $starttime );
		update_post_meta( $post_id, 'endtime', $endtime );
		update_post_meta( $post_id, 'location', $location );
		update_post_meta( $post_id, 'address', $address );
		update_post_meta( $post_id, 'address2', $address2 );
		update_post_meta( $post_id, 'city', $city );
		update_post_meta( $post_id, 'state', $state );
		update_post_meta( $post_id, 'zip', $zip );
		update_post_meta( $post_id, 'country', $country );
		update_post_meta( $post_id, 'country_name', $country_name );
		//sponsor
		update_post_meta( $post_id, 'name', $name );
		update_post_meta( $post_id, 'phone', $phone );
		update_post_meta( $post_id, 'contactname', $contactname );
		update_post_meta( $post_id, 'contactmail', $contactmail );
		update_post_meta( $post_id, 'url', $url );
		//Save Recurrence Meta
		update_post_meta( $post_id, 'eventtype', $eventtype );
		update_post_meta( $post_id, 'recurtype', $recurtype );
		update_post_meta( $post_id, 'dailytype', $dailytype );
		update_post_meta( $post_id, 'daily_ndays', $daily_ndays );
		update_post_meta( $post_id, 'weekly_nweeks', $weekly_nweeks );
		update_post_meta( $post_id, 'weekly_weekdays', $weekly_weekdays );
		update_post_meta( $post_id, 'recurmonthly', $recurmonthly );
		update_post_meta( $post_id, 'monthly_dom', $monthly_dom );
		update_post_meta( $post_id, 'monthly_nmonths', $monthly_nmonths );
		update_post_meta( $post_id, 'monthly_interval', $monthly_interval );
		update_post_meta( $post_id, 'monthly_weekdays', $monthly_weekdays );

		if ( stristr( $monthly_months, '13' ) ) {
			update_post_meta( $post_id, 'monthly_months', '13' );
		} else {
			update_post_meta( $post_id, 'monthly_months', $monthly_months );
		}

		update_post_meta( $post_id, 'recuryearly', $recuryearly );
		update_post_meta( $post_id, 'yearly_day', $yearly_day );
		update_post_meta( $post_id, 'yearly_dommonth', $yearly_dommonth );
		update_post_meta( $post_id, 'yearly_interval', $yearly_interval );
		update_post_meta( $post_id, 'yearly_weekday', $yearly_weekday );
		update_post_meta( $post_id, 'yearly_doymonth', $yearly_daymonth );
		//Additional Information
		update_post_meta( $post_id, 'cost', $cost );
		update_post_meta( $post_id, 'age', $age );
		update_post_meta( $post_id, 'featured', $featured );
		update_post_meta( $post_id, 'readersubmitted', $readersubmitted );
		update_post_meta( $post_id, 'photodescription', $photodescription );

		do_action( 'event_calendar_pro_event_saved', $post_id, $posted );
	}

}
