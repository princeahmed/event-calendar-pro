<?php

namespace WebPublisherPro\EventCalendarPro;

class Install {

	public $events_listing_page_id = null;
	public $event_submit_page_id = null;

	/**
	 * Install constructor.
	 */
	public function __construct() {
		register_activation_hook( WPECP_FILE, array( __CLASS__, 'install' ) );
		//add_filter( 'cron_schedules', array( __CLASS__, 'cron_schedules' ) );
		//register_activation_hook( WPECP_FILE, array( $this, 'ecp_plugin_activate_cb' ) );
		//register_deactivation_hook( WPECP_FILE, array( $this, 'ecp_plugin_deactivate_cb' ) );
	}

	public static function install() {
		if ( get_option( 'event_calendar_pro_install_date' ) ) {
			return;
		}

		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'event_calendar_pros_installing' ) ) {
			return;
		}

		self::create_options();
		self::create_tables();
		self::create_roles();
		self::create_cron_jobs();

		delete_transient( 'event_calendar_pros_installing' );
	}

	/**
	 * Save option data
	 */
	public static function create_options() {
		//save db version
		update_option( 'wpcp_version', WPECP_VERSION );

		//save install date
		update_option( 'event_calendar_pros_install_date', current_time( 'timestamp' ) );
	}

	private static function create_tables() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}
		$table_schema = [
			"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ecp_events` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`post_id` int(11) unsigned NOT NULL,
					`start_date` date default '0000-00-00',
					`end_date` date default '0000-00-00',
					`start_time` time default '00:00',
					`end_time` time default '00:00',
					PRIMARY KEY (`id`)
					) $collate;",
		];
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		foreach ( $table_schema as $table ) {
			dbDelta( $table );
		}
	}

	/**
	 * Create roles and capabilities.
	 */
	private static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new \WP_Roles();
		}

		// Customer role.
		add_role(
			'userrole',
			__( 'User Role', 'event-calendar-pro' ),
			self::get_caps( 'userrole' )
		);

		//add all new caps to admin
		$admin_capabilities = self::get_caps( 'administrator' );

		foreach ( $admin_capabilities as $cap ) {
			$wp_roles->add_cap( 'administrator', $cap );
		}
	}

	/**
	 * @param $role
	 *
	 * @return array
	 */
	private static function get_caps( $role ) {
		$caps = [
			'userrole'      => [],
			'administrator' => [],
		];

		return $caps[ $role ];
	}

	/**
	 * Create cron jobs (clear them first).
	 */
	private static function create_cron_jobs() {
		wp_clear_scheduled_hook( 'event_calendar_pro_daily_cron' );
		wp_schedule_event( time(), 'daily', 'event_calendar_pro_daily_cron' );
	}

	/**
	 * Add more cron schedules.
	 *
	 * @param  array $schedules List of WP scheduled cron jobs.
	 *
	 * @return array
	 */
	public static function cron_schedules( $schedules ) {
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display'  => __( 'Monthly', 'event-calendar-pro' ),
		);

		return $schedules;
	}

//	public function ecp_plugin_activate_cb() {
//		/**
//		 * Create Events Listing and Submit Events Pages
//		 */
//
//		$events_listing_page_id = wp_insert_post( array(
//			'post_title'   => apply_filters( 'event_calendar_pro_page_title', 'Calendar' ),
//			'post_name'    => sanitize_title( apply_filters( 'event_calendar_pro_page_title', 'Calendar' ) ),
//			'post_content' => '[event_calendar_pro_event_list]',
//			'post_status'  => 'publish',
//			'post_type'    => 'page',
//		) );
//
//		if ( ! empty( $events_listing_page_id ) ) {
//			update_post_meta( $events_listing_page_id, '_wp_page_template', apply_filters( 'event_calendar_pro_page_template', 'page-templates/event-listing-page.php' ) );
//		}
//
//		$event_submit_page_id = wp_insert_post( array(
//			'post_title'   => apply_filters( 'event_calendar_pro_submit_page_title', 'Submit an Event' ),
//			'post_name'    => sanitize_title( apply_filters( 'event_calendar_pro_submit_page_title', 'Submit an Event' ) ),
//			'post_content' => '[event_calendar_pro_form]',
//			'post_status'  => 'publish',
//			'post_type'    => 'page',
//		) );
//
//		$pages = array(
//			'events_page'       => $events_listing_page_id,
//			'event_submit_page' => $event_submit_page_id,
//		);
//
//		/**
//		 * Update Event Calendar Page Option Settings
//		 */
//		add_option( 'event_calendar_pro_page_settings', $pages );
//
//		/**
//		 * clear the permalinks after the post type has been registered
//		 */
//		flush_rewrite_rules();
//	}

//	public function ecp_plugin_deactivate_cb() {
//		/**
//		 * Delete Events Pages
//		 */
//		$events_listing_page_id = ecp_get_settings( 'events_page', false, 'event_calendar_pro_page_settings' );
//		$event_submit_page_id   = ecp_get_settings( 'event_submit_page', false, 'event_calendar_pro_page_settings' );
//
//		//wp_die($events_listing_page_id);
//
//		wp_delete_post( $events_listing_page_id, true );
//		wp_delete_post( $event_submit_page_id, true );
//
//		/**
//		 * Delete Event Calendar Page Option Settings
//		 */
//		delete_option( 'event_calendar_pro_page_settings' );
//
//		/**
//		 * clear the permalinks after the post type has been registered
//		 */
//		flush_rewrite_rules();
//	}

}

new Install();
