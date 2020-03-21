<?php

namespace WebPublisherPro\EventCalendarPro;

class ShortCode {

	/**
	 * ShortCode constructor.
	 */
	public function __construct() {
		add_shortcode( 'event_calendar_pro_event_list', array( $this, 'render_event_list' ) );
		add_shortcode( 'event_calendar_pro_event_submit', array( $this, 'render_event_submit' ) );
		add_shortcode( 'event_calendar_pro_user_registration', array( $this, 'render_user_registration_html' ) );
	}

	public function render_event_list( $atts ) {

		$atts = shortcode_atts( array(
			'per_page' => '10',
			'category' => null
		), $atts );

		$keyword    = ! empty( $_GET['search_keyword'] ) ? trim($_GET['search_keyword']) : '';
		$category   = ! empty( $_GET['event_category'] ) ? sanitize_key( $_GET['event_category'] ) : '';
		$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$start_date = ! empty( $_GET['start_date'] ) ? sanitize_key( $_GET['start_date'] ) : '';
		$end_date   = ! empty( $_GET['end_date'] ) ? sanitize_key( $_GET['end_date'] ) : '';
		$date       = ! empty( $_GET['date'] ) ? sanitize_key( $_GET['date'] ) : '';
		$perpage    = $atts['per_page'];

		if ( ! empty( $atts['category'] ) ) {
			$category = $atts['category'];
		}

		$query_args = apply_filters( 'ecp_events_output_query_args', [
			'offset'     => ! empty( $paged ) ? ( $paged - 1 ) * $perpage : 0,
			'categories' => ! empty( $category ) ? $category : null,
			'search'     => ! empty( $keyword ) ? $keyword : null,
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'number'     => $perpage,
		] );

		if ( ! empty( $start_date ) || ! empty( $end_date ) ) {
			$query_args['date'] = '';
		}

		global $events;
		$events   = ecp_get_event_list( $query_args );
		$total    = ecp_get_event_list( $query_args, true );
		$max_page = ceil( $total / $perpage );

		ob_start();
		
		if ( ! empty( $events ) ) {
			ecp_get_template( 'lists/main.php', [ 'max_page' => $max_page, 'paged' => $paged, 'events' => $events ] );
		} else {
			ecp_get_template( 'lists/no-event.php' );

		}

		$html = ob_get_clean();

		return apply_filters( 'ecp_shortcode_events_list_html', $html );
	}

	public function render_event_submit() {

		if ( is_user_logged_in() ) {
			ob_start();
			ecp_get_template( 'submit-event/form-start.php' );
			ecp_get_template( 'submit-event/message.php' );
			ecp_get_template( 'submit-event/post-field.php' );
			ecp_get_template( 'submit-event/date-time.php' );
			ecp_get_template( 'submit-event/recurrence.php' );
			ecp_get_template( 'submit-event/location.php' );
			ecp_get_template( 'submit-event/sponsor.php' );
			ecp_get_template( 'submit-event/information.php' );
			ecp_get_template( 'submit-event/captcha.php' );
			ecp_get_template( 'submit-event/form-end.php' );
			ecp_get_editor_content( 'ecp_event_desc', array( 'quicktags' => false ) );
			$html = ob_get_clean();
			echo $html;
		} else {
			ecp_get_template('user-registration.php');
		}
	}

	public function render_user_registration_html(){
		ecp_get_template( 'user-registration.php' );
	}

}
