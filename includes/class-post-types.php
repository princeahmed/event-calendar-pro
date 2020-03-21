<?php

namespace WebPublisherPro\EventCalendarPro;

class PostTypes {
	/**
	 * PostTypes constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Get all labels from taxonomies
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
		$labels = array(
			'name'              => sprintf( _x( '%s', 'taxonomy general name', 'event-calendar-pro' ), $plural ),
			'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'event-calendar-pro' ), $singular ),
			'search_items'      => sprintf( __( 'Search %', 'event-calendar-pro' ), $plural ),
			'all_items'         => sprintf( __( 'All %s', 'event-calendar-pro' ), $plural ),
			'parent_item'       => sprintf( __( 'Parent %s', 'event-calendar-pro' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'event-calendar-pro' ), $singular ),
			'edit_item'         => sprintf( __( 'Edit %s', 'event-calendar-pro' ), $singular ),
			'update_item'       => sprintf( __( 'Update %s', 'event-calendar-pro' ), $singular ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'event-calendar-pro' ), $singular ),
			'new_item_name'     => sprintf( __( 'New % Name', 'event-calendar-pro' ), $singular ),
			'menu_name'         => __( $menu_name, 'event-calendar-pro' ),
		);

		return $labels;
	}

	/**
	 * Register custom post types
	 */
	public function register_post_types() {
		register_post_type( 'pro_event', array(
			'labels'              => $this->get_posts_labels( 'Events', __( 'Event', 'event-calendar-pro' ), __( 'Events', 'event-calendar-pro' ) ),
			'hierarchical'        => false,
			'supports'            => apply_filters('pro_event_post_supports',array( 'title', 'editor', 'thumbnail')),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array(
				'slug'       => apply_filters( 'pro_event_slug', 'events' ),
				'with_front' => false
			),
			'capability_type'     => 'post',
		) );

	}

	/**
	 * Get all labels from post types
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected static function get_posts_labels( $menu_name, $singular, $plural ) {
		$labels = array(
			'name'               => $singular,
			'all_items'          => sprintf( __( "All %s", 'event-calendar-pro' ), $plural ),
			'singular_name'      => $singular,
			'add_new'            => sprintf( __( 'New %s', 'event-calendar-pro' ), $singular ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'event-calendar-pro' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'event-calendar-pro' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'event-calendar-pro' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'event-calendar-pro' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'event-calendar-pro' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found', 'event-calendar-pro' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'event-calendar-pro' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'event-calendar-pro' ), $singular ),
			'menu_name'          => $menu_name,
		);

		return $labels;
	}

	/**
	 * Register custom taxonomies
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomies() {
		register_taxonomy( 'event_category', array( 'pro_event' ), array(
			'hierarchical'      => true,
			'labels'            => $this->get_posts_labels( 'Event Category', __( 'Event Category', 'event-calendar-pro' ), __( 'Event Categories', 'event-calendar-pro' ) ),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => apply_filters( 'pro_event_category_slug', 'event-category' ) )
		) );
	}
}
