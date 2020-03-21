<?php
/**
 * get template
 *
 * since 1.0.0
 * @param        $template_name
 * @param array  $args
 * @param string $template_path
 * @param string $default_path
 */
function ecp_get_template( $template_name, $args = array(), $template_path = 'event-calendar-pro', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		// Please, forgive us.
		extract( $args ); // phpcs:ignore WordPress.Functions.DontExtract.extract_extract
	}

	include ecp_locate_template( $template_name, $template_path, $default_path );
}

/**
 * Locate template
 *
 * since 1.0.0
 * @param        $template_name
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function ecp_locate_template( $template_name, $template_path = 'event-calendar-pro', $default_path = '' ) {
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template.
	if ( ! $template && false !== $default_path ) {
		$default_path = $default_path ? $default_path : WPECP_TEMPLATES_DIR;
		if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}
	}

	// Return what we found.
	return apply_filters( 'ecp_locate_template', $template, $template_name, $template_path );
}

/**
 * Gets template part (for templates in loops).
 *
 * since 1.0.0
 * @param        $slug
 * @param string $name
 * @param string $template_path
 * @param string $default_path
 */
function ecp_get_template_part( $slug, $name = '', $template_path = 'event-calendar-pro', $default_path = '' ) {
	$template = '';

	if ( $name ) {
		$template = ecp_locate_template( "{$slug}-{$name}.php", $template_path, $default_path );
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ecp/slug.php.
	if ( ! $template ) {
		$template = ecp_locate_template( "{$slug}.php", $template_path, $default_path );
	}

	if ( $template ) {
		load_template( $template, false );
	}
}

//function prefix event_calendar_pro
add_filter( "template_include", "themplate_load_event_archive" );
function themplate_load_event_archive( $template ) {
	if ( is_post_type_archive( 'pro_event') ) {

		if ( locate_template( 'pro_event-archive.php' ) ) {
			$template = locate_template( 'pro_event-archive.php' );
		} else {
			$template = WPECP_TEMPLATES_DIR . '/archive.php';
		}
	}
	
	return $template;
}

function ecp_view_single_event($content) {

	global $post;

	if (!isset($post->post_type)) {
		return;
	}
	if ($post->post_type !== 'pro_event') {
		return $content;
	}

	ob_start();
	ecp_get_template('single/main.php');
	$content = ob_get_clean();

	return $content;
}

add_filter('the_content', 'ecp_view_single_event');

function ecp_get_thumbnail($post_id){
	$thumbnail = get_the_post_thumbnail($post_id);
	return apply_filters('ecp_get_thumbnail', $thumbnail);
}


