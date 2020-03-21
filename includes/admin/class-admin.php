<?php

namespace WebPublisherPro\EventCalendarPro\Admin;

class Admin {
	/**
	 * The single instance of the class.
	 *
	 * @var Admin
	 * @since 1.0.0
	 */
	protected static $init = null;

	/**
	 * Frontend Instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Admin - Main instance.
	 */
	public static function init() {
		if ( is_null( self::$init ) ) {
			self::$init = new self();
			self::$init->setup();
		}

		return self::$init;
	}

	/**
	 * Initialize all Admin related stuff
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
	 * Includes all files related to admin
	 */
	public function includes() {
		require_once dirname( __FILE__ ) . '/class-admin-menu.php';
		require_once dirname( __FILE__ ) . '/class-metabox.php';
		require_once dirname( __FILE__ ) . '/class-settings-api.php';
		require_once dirname( __FILE__ ) . '/class-settings.php';
		require_once WPECP_INCLUDES . '/class-form-handler.php';
		require_once WPECP_INCLUDES . '/class-widget.php';
		require_once WPECP_INCLUDES . '/class-ajax.php';
		require_once WPECP_INCLUDES . '/hooks-functions.php';
		require_once WPECP_INCLUDES . '/template-functions.php';
	}

	private function init_hooks() {
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Fire off all the instances
	 *
	 * @since 1.0.0
	 */
	protected function instance() {
		new Admin_Menu();
		new MetaBox();
		new Settings();
		new \WebPublisherPro\EventCalendarPro\FormHandler();
		new \WebPublisherPro\EventCalendarPro\ECP_Widget();
	}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 *
	 * @since 1.0.0
	 */
	public function buffer() {
		ob_start();
	}


	public function enqueue_scripts() {

		wp_enqueue_style( 'date-timepicker', WPECP_ASSETS_URL . "/js/vendor/date-time-picker/date-time-picker.css" );
		wp_enqueue_style( 'event-calendar-pro', WPECP_ASSETS_URL . "/css/admin.css", [], WPECP_VERSION );

		//scripts
		wp_enqueue_script( 'ecp-event-reccurrence', WPECP_ASSETS_URL . "/js/vendor/event-recurrence.js", [ 'jquery' ], WPECP_VERSION, true );
		wp_enqueue_script( 'event-calendar-pro', WPECP_ASSETS_URL . "/js/admin/admin.js", [ 'jquery' ], WPECP_VERSION, true );
		wp_localize_script( 'event-calendar-pro', 'wpecp', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => 'event-calendar-pro'
		] );

		wp_enqueue_script( 'timepicker', WPECP_ASSETS_URL . "/js/vendor/date-time-picker/time-picker.js", [
			'jquery',
		], WPECP_VERSION, true );

		wp_enqueue_script( 'jquery-ui-datepicker' );
	}


}

Admin::init();
