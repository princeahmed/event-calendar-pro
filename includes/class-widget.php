<?php

namespace WebPublisherPro\EventCalendarPro;

use WP_Widget;

/**
 * Adds ECP_Widget widget.
 */
class ECP_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		add_action( 'widgets_init', array( $this, 'register_ecp_widget' ) );
		parent::__construct(
			'ecp_widget', // Base ID
			esc_html__( 'Event Calendar Pro', 'event-calendar-pro' ),
			array( 'description' => esc_html__( 'Show and Filters Events', 'event-calendar-pro' ) )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $widget_args, $instance ) {

		echo $widget_args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $widget_args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $widget_args['after_title'];
		}

		$events_page = ecp_get_settings( 'events_page', 'calendar', 'event_calendar_pro_page_settings' );

		$date = get_query_var( 'date' );

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
		ecp_get_template( 'widget/main.php', [ 'events' => $events, 'events_page' => $events_page ] );
		$html = ob_get_clean();

		echo $html;

		echo $widget_args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Calendar', 'event-calendar-pro' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'event-calendar-pro' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

	// register ECP_Widget widget
	public function register_ecp_widget() {
		register_widget( 'WebPublisherPro\EventCalendarPro\ECP_Widget' );
	}

}


