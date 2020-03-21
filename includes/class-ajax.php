<?php

namespace WebPublisherPro\EventCalendarPro;

class ECP_Ajax
{

	/**
	 * Ajax constructor.
	 */
	public function __construct() {
		add_action('wp_ajax_ecp_get_event_list', array($this, 'get_event_list'));
		add_action('wp_ajax_nopriv_ecp_get_event_list', array($this, 'get_event_list'));
	}

	public function get_event_list() {
		$date = sanitize_text_field($_REQUEST['date']);
		set_query_var('date', $date);

		if ( empty( $date ) ) {
			$date = date( 'Y-m-d', current_time( 'timestamp' ) );
		}

		$args = array(
			'order_by' => 'event_start_date',
			'order'    => 'DESC',
		);

		if ( ! empty( $date ) ) {
			$args['start_date'] = $date;
		}

		$args = apply_filters('ecp_widget_events_query_args', $args);


		$events = ecp_get_event_list( $args );

		ob_start();
		if($events){
			ecp_get_template( 'widget/list-start.php', ['date' => $date] );
			foreach ( $events as $event ) {
				ecp_get_template( 'widget/list.php', ['event' => $event] );
			}
			ecp_get_template( 'widget/list-end.php');
		}else{
			ecp_get_template( 'widget/no-event.php');
		}
		$html = ob_get_clean();

		wp_send_json_success([
			'html' => $html,
		]);
	}

}

