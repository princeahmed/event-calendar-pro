<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article <?php post_class( 'event-listing b-b-1' ) ?>>

	<div class="event-header">
		<h2 class="event-title">
			<a href="<?php echo get_the_permalink( $event->ID ); ?>"><?php echo apply_filters( 'the_title', $event->post_title ); ?></a>
		</h2>
		<div class="clearfix">

			<p class="event-location pull-right">
				<?php echo ecp_get_event_location( $event->ID ) ?>
			</p>

			<p class="event-date pull-left">
				<?php if ( ! empty( $event->start_date ) ) { ?>

					<?php
					$timing      = '';
					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
					if ( $event->start_date ) {
						$timing .= date( $date_format, strtotime( $event->start_date ) );
					}

					if ( $event->end_date ) {
						$timing .= '  -  ' . date( $date_format, strtotime( $event->end_date ) );
					}

					if ( $event->start_time ) {
						$timing .= ' @ ' . date( $time_format, strtotime( $event->start_time ) );
					}

					if ( ! empty( $timing ) ) {
						echo '<span class="ecp-event-time">' . $timing . '</span>';
					}
					?><?php } ?>

			</p>

		</div>
	</div>

	<div class="event-body">
		<div class="calendar-list-data">
			<?php
			$recurrence = ecp_get_recurrence_message( $event->ID );
			if ( ! empty( $recurrence ) ) {
				echo $recurrence;
			}
			?>

			<div class="event-desc clearfix">
				<div class="calendar-list-image">
					<a href="<?php echo get_the_permalink( $event->ID ); ?>">
						<?php echo get_the_post_thumbnail( $event->ID, 'thumbnail' ); ?>
					</a>
				</div>

				<div class="event-content mb-10">

					<?php

					if ( ! empty( $event->post_excerpt ) ) {
						echo wpautop( $event->post_excerpt );
					} else {
						$excerpt = strip_shortcodes( $event->post_content );
						echo wpautop( wp_trim_words( $excerpt, 20, '' ) );
					} ?>

					<a class="read-more-btn" href="<?php echo get_the_permalink( $event->ID ) ?>"><strong>View event details</strong></a>

				</div>

				<div class="event-categories">
					<?php

					$categories = ecp_get_event_categories( $event->ID );

					if ( ! empty( $categories ) ) {
						$categories = wp_list_pluck( $categories, 'name', 'term_id' );
						foreach ( $categories as $term_id => $name ) {
							echo sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'event_category' => $term_id ), get_the_permalink() ), $name );
						}
					}

					?>
				</div>
			</div>
		</div>
	</div>
</article>
