<?php

namespace WebPublisherPro\EventCalendarPro;

class Frontend {
	/**
	 * The single instance of the class.
	 *
	 * @var Frontend
	 * @since 1.0.0
	 */
	protected static $init = null;

	/**
	 * Frontend Instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Frontend - Main instance.
	 */
	public static function init() {
		if ( is_null( self::$init ) ) {
			self::$init = new self();
			self::$init->setup();
		}

		return self::$init;
	}

	/**
	 * Initialize all frontend related stuff
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function setup() {
		$this->includes();
		$this->init_hooks();
		$this->instance();
	}

	/**
	 * Includes all frontend related files
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {
		require_once dirname( __FILE__ ) . '/template-functions.php';
		require_once dirname( __FILE__ ) . '/class-shortcode.php';
		require_once dirname( __FILE__ ) . '/class-form-handler.php';
		require_once dirname( __FILE__ ) . '/class-widget.php';
		require_once dirname( __FILE__ ) . '/class-ajax.php';
		require_once dirname( __FILE__ ) . '/hooks-functions.php';
	}

	/**
	 * Register all frontend related hooks
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Fire off all the instances
	 *
	 * @since 1.0.0
	 */
	protected function instance() {
		new ShortCode();
		new FormHandler();
		new ECP_Widget();
		new ECP_Ajax();
	}

	/**
	 * Loads all frontend scripts/styles
	 *
	 * @param $hook
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		//styles
		wp_enqueue_style( 'fullcalendar', WPECP_ASSETS_URL . "/js/vendor/fullcalendar/fullcalendar.min.css" );
		wp_enqueue_style( 'date-timepicker', WPECP_ASSETS_URL . "/js/vendor/date-time-picker/date-time-picker.css" );
		wp_enqueue_style( 'event-calendar-pro', WPECP_ASSETS_URL . "/css/frontend.css", [], WPECP_VERSION );

		//scripts
		wp_enqueue_media();
		wp_enqueue_script( 'moment', WPECP_ASSETS_URL . "/js/vendor/moment.min.js", [ 'jquery' ], WPECP_VERSION, true );
		wp_enqueue_script( 'fullcalendar', WPECP_ASSETS_URL . "/js/vendor/fullcalendar/fullcalendar.min.js", [
			'jquery',
			'moment'
		], WPECP_VERSION, true );
		wp_enqueue_script( 'timepicker', WPECP_ASSETS_URL . "/js/vendor/date-time-picker/time-picker.js", [
			'jquery',
			'moment'
		], WPECP_VERSION, true );

		wp_enqueue_script( 'ecp-event-reccurrence', WPECP_ASSETS_URL . "/js/vendor/event-recurrence.js", [ 'jquery'], WPECP_VERSION, true );
		wp_enqueue_script( 'event-calendar-pro', WPECP_ASSETS_URL . "/js/frontend/frontend.js", [ 'jquery', 'wp-util' ], WPECP_VERSION, true );
		wp_localize_script( 'event-calendar-pro', 'wpecp',
			[
				'asset_url' => WPECP_ASSETS_URL,
				'nonce'     => wp_create_nonce( 'event-calendar-pro' )
			] );

		wp_enqueue_script( 'jquery-ui-datepicker' );
	}

}

Frontend::init();
