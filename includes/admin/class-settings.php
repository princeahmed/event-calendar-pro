<?php

namespace WebPublisherPro\EventCalendarPro\Admin;
class Settings {
	private $settings_api;

	function __construct() {
		$this->settings_api = new \Ever_Settings_API();
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	function admin_menu() {
		add_submenu_page( 'edit.php?post_type=pro_event', 'Settings', 'Settings', 'manage_options', 'event_calendar_pro-settings', array(
			$this,
			'settings_page'
		) );
	}

	function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'general_settings',
				'title' => __( 'General Settings', 'event-calendar-pro' )
			),
			array(
				'id'    => 'event_calendar_pro_page_settings',
				'title' => __( 'Page Settings', 'event-calendar-pro' )
			),
			array(
				'id'    => 'ecp_countries',
				'title' => __( 'Countries', 'event-calendar-pro' )
			),
			array(
				'id'    => 'ecp_states',
				'title' => __( 'States', 'event-calendar-pro' )
			),
		);

		return apply_filters( 'event_calendar_pro_settings_sections', $sections );
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$pages           = get_pages();
		$pages           = wp_list_pluck( $pages, 'post_title', 'ID' );
		$settings_fields = array(
			'general_settings' => array(
				array(
					'name'    => 'secret_key',
					'label'   => __( 'Captcha Secret Key', 'event-calendar-pro' ),
					'desc'    => __( 'Google captcha secret key.', 'event-calendar-pro' ),
					'type'    => 'text'
				),
				array(
					'name'    => 'site_key',
					'label'   => __( 'Captcha Site Key', 'event-calendar-pro' ),
					'desc'    => __( 'Google captcha site key.', 'event-calendar-pro' ),
					'type'    => 'text'
				),
			),
			'event_calendar_pro_page_settings' => array(
				array(
					'name'    => 'events_page',
					'label'   => __( 'Events List Page', 'event-calendar-pro' ),
					'desc'    => __( 'The page contains [event_calendar_pro_event_list] shortcode', 'event-calendar-pro' ),
					'type'    => 'select',
					'options' => $pages,
				),
				array(
					'name'    => 'event_submit_page',
					'label'   => __( 'Event Submit Page', 'event-calendar-pro' ),
					'desc'    => __( 'The page contains [event_calendar_pro_event_submit] shortcode', 'event-calendar-pro' ),
					'type'    => 'select',
					'options' => $pages,
				),

				array(
					'name'    => 'user_registration_page',
					'label'   => __( 'User Registration Page', 'event-calendar-pro' ),
					'desc'    => __( 'The page contains [event_calendar_pro_user_registration] shortcode', 'event-calendar-pro' ),
					'type'    => 'select',
					'options' => $pages,
				),

				array(
					'name'  => 'event_notification_email_address',
					'label' => __( 'Email Address ', 'event-calendar-pro' ),
					'type'  => 'text',
					'desc'  => 'Enter the email, That will receive the notification on any Event submission',
				),


			),
			'ecp_countries'                    => array(
				array(
					'name'  => 'country_name',
					'label' => __( 'Country', 'event-calendar-pro' ),
					'desc'  => __( 'Add Country for Event location option', 'event-calendar-pro' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'country_code',
					'label' => __( 'Country Code', 'event-calendar-pro' ),
					'desc'  => __( 'Country code. Ex: CA for Canada', 'event-calendar-pro' ),
					'type'  => 'text',
				)
			),
			'ecp_states'                       => array(
				array(
					'name'  => 'state_name',
					'label' => __( 'State Name', 'event-calendar-pro' ),
					'desc'  => __( 'Enter the state name', 'event-calendar-pro' ),
					'type'  => 'text',
				),
				array(
					'name'  => 'state_code',
					'label' => __( 'State Code', 'event-calendar-pro' ),
					'desc'  => __( 'State code. Ex: AL for Alabama', 'event-calendar-pro' ),
					'type'  => 'text',
				),
				array(
					'name'    => 'state_country_code',
					'label'   => __( 'Country Code', 'event-calendar-pro' ),
					'desc'    => __( 'Select the country code of the state', 'event-calendar-pro' ),
					'type'    => 'select',
					'options' => array(
						'Al' => 'Alabama',
						'Cl' => 'California',
					)
				),
			),
		);

		return apply_filters( 'event_calendar_pro_settings_fields', $settings_fields );
	}

	function settings_page() {
		?>
		<?php
		echo '<div class="wrap">';
		echo sprintf( "<h2>%s</h2>", __( 'Event Calendar Pro Settings', 'event-calendar-pro' ) );
		$this->settings_api->show_settings();
		echo '</div>';
	}

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	function get_pages() {
		$pages         = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		return $pages_options;
	}
}
