<?php
//function prefix event_calendar_pro


/**
 * get event meta
 *
 */
function ecp_get_event_meta( $event_id, $key = '', $default = false ) {
	$meta_value = get_post_meta( $event_id, $key, true );

	return empty( $meta_value ) ? $default : $meta_value;
}

/**
 * Return save setting options
 *
 * @param        $key
 * @param string $default
 * @param string $section
 *
 * @return string
 */
function ecp_get_settings( $key, $default = '', $section = '' ) {
	$option = get_option( $section, [] );

	return ! empty( $option[ $key ] ) ? $option[ $key ] : $default;
}


/**
 * get the selected page url from settings options
 *
 * @param $page
 *
 * @return false|string
 */
function ecp_get_page_url( $page ) {
	$page_id   = ecp_get_settings( $page, '', 'event_calendar_pro_page_settings' );
	$permalink = get_permalink( $page_id );

	return ! empty( $page_id ) && $permalink ? $permalink : '';
}

/**
 * Retrun the event_meta image preview
 *
 * @param       $post_id
 * @param array $args
 *
 * @return string
 */

/**
 * Return all the event posts
 *
 * @param array $args
 */

function ecp_get_event_list( $args = array(), $count = false ) {

	global $wpdb;

	$post_type = 'pro_event';

	$args = wp_parse_args( $args, array(
		'number'     => get_option( 'posts_per_page' ),
		'offset'     => 0,
		'search'     => '',
		'date'       => date( 'Y-m-d', current_time( 'timestamp' ) ),
		'start_date' => '',
		'end_date'   => '',
		'location'   => '',
		'categories' => '',
		'orderby'    => 'events.start_date',
		'order'      => 'ASC',
	) );

	if ( empty( $args['start_date'] ) && empty( $args['date'] ) ) {
		$args['date'] = date( 'Y-m-d', current_time( 'timestamp' ) );
	}

	if ( $args['orderby'] == 'id' ) {
		$args['orderby'] = 'p.ID';
	}

	if ( $args['orderby'] == 'title' ) {
		$args['orderby'] = 'p.post_title';
	}

	if ( $args['number'] < 1 ) {
		$args['number'] = 9999999;
	}

	$where = ' WHERE 1=1 ';
	$join  = "LEFT JOIN {$wpdb->prefix}ecp_events events on events.post_id=p.ID ";

	// Specific id
	if ( ! empty( $args['id'] ) ) {

		if ( is_array( $args['id'] ) ) {
			$ids = implode( ',', array_map( 'intval', $args['id'] ) );
		} else {
			$ids = intval( $args['id'] );
		}

		$where .= " AND p.ID IN( {$ids} ) ";
	}

	// exclude id
	if ( ! empty( $args['not_in'] ) ) {

		if ( is_array( $args['not_in'] ) ) {
			$ids = implode( ',', array_map( 'intval', $args['not_in'] ) );
		} else {
			$ids = intval( $args['not_in'] );
		}

		$where .= " AND p.ID NOT IN( {$ids} ) ";
	}

	if ( ! empty( $args['search'] ) ) {
		$where .= " AND ( p.post_title LIKE '%%" . esc_sql( $args['search'] ) . "%%' OR p.post_content LIKE '%%" . esc_sql( $args['search'] ) . "%%')";
	}

	//Default list query (Select all events, which enddate is >= currentdate)
	if ( ! empty( $args['date'] ) ) {
		$where .= " AND date(events.start_date) >= date('{$args['date']}') ";
	}

	if ( ! empty( $args['start_date'] ) && ! empty( $args['end_date'] ) ) {
		$where .= " AND date(events.start_date) >= date('{$args['start_date']}') AND  date(events.start_date) <= date('{$args['end_date']}') ";
	} else {

		if ( ! empty( $args['start_date'] ) ) {
			$where .= " AND date(events.start_date) = date('{$args['start_date']}') ";
		}

		if ( ! empty( $args['end_date'] ) ) {
			$where .= " AND date(events.start_date) <= date('{$args['end_date']}') ";
		}

	}

	if ( ! empty( $args['categories'] ) ) {
		if ( is_array( $args['categories'] ) ) {
			$categories = implode( ',', array_map( 'intval', $args['categories'] ) );
		} else {
			$categories = intval( $args['categories'] );
		}

		$join  .= " INNER JOIN $wpdb->term_relationships tr on tr.object_id=p.ID";
		$where .= " AND tr.term_taxonomy_id IN ('$categories') ";
	}

	if ( ! empty( $args['featured'] ) ) {
		$join .= " LEFT JOIN $wpdb->postmeta featured ON featured.post_id = p.ID ";
	}

	$join .= " LEFT JOIN $wpdb->postmeta m ON m.post_id = p.ID ";
	$join = apply_filters( 'ecp_sql_join', $join, $args );

	$meta_key = apply_filters( 'ecp_select_meta_key', 'startdate' );
	$where    .= " AND m.meta_key = '$meta_key' ";

	$where .= " AND p.post_status = 'publish' AND p.post_type = '{$post_type}' ";

	if ( ! empty( $args['featured'] ) ) {
		$where .= " AND featured.meta_key = 'featured' AND featured.meta_value = 'yes' ";
	}

	$where = apply_filters( 'ecp_sql_where', $where, $args );

	$args['orderby'] = esc_sql( $args['orderby'] );
	$args['order']   = esc_sql( $args['order'] );

	//if count
	if ( $count ) {
		$total = $wpdb->get_col( "select p.ID FROM $wpdb->posts p $join $where GROUP BY p.ID " );

		return count( $total );
	}

	$distinct = apply_filters( 'ecp_select_distinct_event', null );
	$group_by = apply_filters( 'ecp_group_by_event', ' GROUP BY p.ID ' );

	$sql = $wpdb->prepare( "SELECT {$distinct} m.meta_value, p.ID, p.post_title, p.post_content, p.post_excerpt, events.start_date, events.end_date, events.start_time, events.end_time FROM $wpdb->posts p $join $where {$group_by} ORDER BY  {$args['orderby']} {$args['order']} , events.start_time ASC LIMIT %d,%d ; ", absint( $args['offset'] ), absint( $args['number'] ) );

	return $wpdb->get_results( $sql );
}

/**
 * @param        $post_id
 * @param string $taxonomy
 * @param array $args
 *
 * @return array
 */

function ecp_get_event_categories( $post_id = '', $taxonomy = 'event_category', $args = [] ) {

	$args = wp_parse_args( $args, array(
		'orderby' => 'name',
		'order'   => 'ASC',
		'fields'  => 'all'
	) );

	$terms = wp_get_post_terms( $post_id, $taxonomy, $args );

	return $terms;
}

function ecp_get_editor_content( $editor_id, $settings = array() ) {
	ob_start();
	wp_editor( '', $editor_id, $settings );

	return ob_get_clean();
}

function ecp_get_all_event_categories( $key = 'slug' ) {

	return wp_list_pluck( get_terms( 'event_category', array( 'hide_empty' => false ) ), 'name', $key );
}

function ecp_get_feedback_message( $code ) {
	switch ( $code ) {
		case 'invalid':
			return __( 'Something is not correct, please correct the form and try again', 'event-calendar-pro' );
			break;
		case 'invalid_captcha':
			return __( '<p class="message text-danger">Invalid Capcha code, please fix the error and resubmit the form.</p>', 'event-calendar-pro' );
			break;
		case 'invalid_email':
			return __( '<p class="message text-danger">Invalid Email, please enter a valid email and resubmit the form.</p>', 'event-calendar-pro' );
			break;
		case 'email_exists':
			return __( '<p class="message text-danger">The email is already exists, Try with different email.</p>', 'event-calendar-pro' );
			break;
		case 'empty_password':
			return __( '<p class="message text-danger">The password is empty.</p>', 'event-calendar-pro' );
			break;
		case 'successfull_rgistration':
			return __( '<p class="message text-success">Successfully, Registered, Please login now.</p>', 'event-calendar-pro' );
			break;
		case 'event_submitted':
			return __( '<p class="message text-success">Event Successfully Submitted.</p>', 'event-calendar-pro' );
			break;
		case 'success_submit':
			return __( 'Your event posting has been saved.', 'event-calendar-pro' );
			break;
	}
}

function ecp_do_math( $num1, $num2, $operator ) {
	switch ( $operator ) {
		case '+':
			return $num1 + $num2;
		case '-':
			return $num1 - $num2;
		case '*':
			return $num1 * $num2;
		case '/':
			return $num1 / $num2;
	}
}

function ecp_num_to_name( $nums = [] ) {
	$names = [];
	foreach ( $nums as $num ) {
		switch ( $num ) {
			case 1:
				$names[] = str_replace( $num, 'January', $num );
				break;
			case 2:
				$names[] = str_replace( $num, 'February', $num );
				break;
			case 3:
				$names[] = str_replace( $num, 'March', $num );
				break;
			case 4:
				$names[] = str_replace( $num, 'April', $num );
				break;
			case 5:
				$names[] = str_replace( $num, 'May', $num );
				break;
			case 6:
				$names[] = str_replace( $num, 'June', $num );
				break;
			case 7:
				$names[] = str_replace( $num, 'July', $num );
				break;
			case 8:
				$names[] = str_replace( $num, 'August', $num );
				break;
			case 9:
				$names[] = str_replace( $num, 'September', $num );
				break;
			case 10:
				$names[] = str_replace( $num, 'October', $num );
				break;
			case 11:
				$names[] = str_replace( $num, 'November', $num );
				break;
			case 12:
				$names[] = str_replace( $num, 'December', $num );
				break;
			case 13:
				$names[] = str_replace( $num, 'Every Month', $num );
				break;
		}
	}

	return $names;
}

function ecp_get_events_slug() {
	return apply_filters( 'events', 'ecp_events_slug' );
}

function ecp_paginate( $max_page, $paged, $range = 4 ) {
	// We need the pagination only if there is more than 1 page
	if ( $max_page > 1 ) {
		if ( ! $paged ) {
			$paged = 1;
		}

		echo '<div class="postpagination">';

		// To the previous page
		previous_posts_link( 'Prev' );

		if ( $max_page > $range + 1 ) :
			if ( $paged >= $range ) {
				echo '<a href="' . get_pagenum_link( 1 ) . '">1</a>';
			}
			if ( $paged >= ( $range + 1 ) ) {
				echo '<span class="page-numbers">&hellip;</span>';
			}
		endif;

		// We need the sliding effect only if there are more pages than is the sliding range
		if ( $max_page > $range ) {
			// When closer to the beginning
			if ( $paged < $range ) {
				for ( $i = 1; $i <= ( $range + 1 ); $i ++ ) {
					echo ( $i != $paged ) ? '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>' : '<span class="this-page">' . $i . '</span>';
				}
				// When closer to the end
			} elseif ( $paged >= ( $max_page - ceil( ( $range / 2 ) ) ) ) {
				for ( $i = $max_page - $range; $i <= $max_page; $i ++ ) {
					echo ( $i != $paged ) ? '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>' : '<span class="this-page">' . $i . '</span>';
				}
				// Somewhere in the middle
			} elseif ( $paged >= $range && $paged < ( $max_page - ceil( ( $range / 2 ) ) ) ) {
				for ( $i = ( $paged - ceil( $range / 2 ) ); $i <= ( $paged + ceil( ( $range / 2 ) ) ); $i ++ ) {
					echo ( $i != $paged ) ? '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>' : '<span class="this-page">' . $i . '</span>';
				}
			}
			// Less pages than the range, no sliding effect needed
		} else {
			for ( $i = 1; $i <= $max_page; $i ++ ) {
				echo ( $i != $paged ) ? '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>' : '<span class="this-page">' . $i . '</span>';
			}
		}
		if ( $max_page > $range + 1 ) :
			// On the last page, don't put the Last page link
			if ( $paged <= $max_page - ( $range - 1 ) ) {
				echo '<span class="page-numbers">&hellip;</span><a href="' . get_pagenum_link( $max_page ) . '">' . $max_page . '</a>';
			}
		endif;

		// Next page
		next_posts_link( 'Next', $max_page );

		echo '</div><!-- postpagination -->';
	}
}

function ecp_get_dates_in_period( $start_date, $end_date, $step = '1', $unit = 'D', $modify = null ) {

	if ( ! ecp_is_valid_date( $start_date ) || ! ecp_is_valid_date( $end_date ) ) {
		return false;
	}

	$dates = [];

	$start = new DateTime( $start_date );

	if ( $modify ) {
		$start->modify( $modify );
	}

	$end = new DateTime( $end_date );

	//include the enddate
	$end->setTime(0,0,1);

	$period = new DatePeriod(
		$start,
		new DateInterval( "P{$step}{$unit}" ),
		$end
	);

	foreach ( $period as $key => $date ) {
		$dates[] = $date->format( 'Y-m-d' );
	}

	return $dates;
}

function event_calendar_pro_date_sort( $a, $b ) {
	return strtotime( $a ) - strtotime( $b );
}

function event_calendar_pro_generate_events( $post_id ) {
	global $wpdb;
	$start_date         = get_post_meta( $post_id, 'startdate', true );
	$end_date           = get_post_meta( $post_id, 'enddate', true );
	$start_time         = get_post_meta( $post_id, 'starttime', true );
	$end_time           = get_post_meta( $post_id, 'endtime', true );
	$event_type         = get_post_meta( $post_id, 'eventtype', true );
	$recur_type         = get_post_meta( $post_id, 'recurtype', true );
	$recur_monthly_type = get_post_meta( $post_id, 'recurmonthly', true );
	$recur_yearly_type  = get_post_meta( $post_id, 'recuryearly', true );
	$dates              = array();

	if ( 'recurring' == $event_type && $recur_type == 'daily' ) {
		$daily_ndays = (int) get_post_meta( $post_id, 'daily_ndays', true );
		$daily_ndays = ! empty( $daily_ndays ) ? $daily_ndays : 1;

		$dates = ecp_get_dates_in_period( $start_date, $end_date, $daily_ndays );

	} elseif ( 'recurring' == $event_type && $recur_type == 'weekly' ) {
		$weekly_nweeks   = (int) get_post_meta( $post_id, 'weekly_nweeks', true );
		$weekly_weekdays = get_post_meta( $post_id, 'weekly_weekdays', true );
		$weekly_weekdays = explode( ',', $weekly_weekdays );
		foreach ( $weekly_weekdays as $weekly_weekday ) {
			$dates = array_merge( $dates, ecp_get_dates_in_period( $start_date, $end_date, $weekly_nweeks, 'W', $weekly_weekday ) );
		}
	} elseif ( 'recurring' == $event_type && $recur_type == 'monthly' && $recur_monthly_type == 'dom' ) {
		$monthly_dom     = (int) get_post_meta( $post_id, 'monthly_dom', true );
		$monthly_nmonths = (int) get_post_meta( $post_id, 'monthly_nmonths', true );
		//adjust as it will increase
		$monthly_dom  = $monthly_dom > 0 ? $monthly_dom - 1 : 0;
		$starting_day = date( 'Y-m-01', strtotime( $start_date ) );
		$dates        = ecp_get_dates_in_period( $starting_day, $end_date, $monthly_nmonths, 'M', "+{$monthly_dom} day" );

	} elseif ( 'recurring' == $event_type && $recur_type == 'monthly' && $recur_monthly_type == 'nday' ) {
		$monthly_intervals = get_post_meta( $post_id, 'monthly_interval', true );
		$monthly_months    = get_post_meta( $post_id, 'monthly_months', true );
		$monthly_weekdays  = get_post_meta( $post_id, 'monthly_weekdays', true );
		$monthly_intervals = explode( ',', $monthly_intervals );
		$monthly_weekdays  = explode( ',', $monthly_weekdays );
		$monthly_months    = explode( ',', $monthly_months );
		$monthly_months    = in_array( 13, $monthly_months ) ? range( 1, 12 ) : $monthly_months;
		$start_year        = date( 'Y', strtotime( $start_date ) );
		$end_year          = date( 'Y', strtotime( $end_date ) );
		$years             = range( $start_year, $end_year );
		foreach ( $years as $year ) {
			foreach ( $monthly_months as $monthly_month ) {

				$date_obj   = DateTime::createFromFormat( '!m', $monthly_month );
				$month_name = ! empty( $date_obj ) ? $date_obj->format( 'F' ) : '';

				foreach ( $monthly_intervals as $monthly_interval ) {
					foreach ( $monthly_weekdays as $monthly_weekday ) {
						$dates [] = date( 'Y-m-d', strtotime( "$monthly_interval $monthly_weekday of $month_name $year" ) );
					}
				}
			}
		}
	} elseif ( 'recurring' == $event_type && $recur_type == 'yearly' && $recur_yearly_type == 'nday' ) {
		$yearly_day   = (int) get_post_meta( $post_id, 'yearly_day', true );
		$yearly_month = (int) get_post_meta( $post_id, 'yearly_dommonth', true );

		$start_year = date( 'Y', strtotime( $start_date ) );
		$end_year   = date( 'Y', strtotime( $end_date ) );
		$years      = range( $start_year, $end_year );
		foreach ( $years as $year ) {
			$dates [] = date( 'Y-m-d', strtotime( "$year-$yearly_month-$yearly_day" ) );
		}

	} elseif ( 'recurring' == $event_type && $recur_type == 'yearly' && $recur_yearly_type == 'doy' ) {
		$yearly_interval = get_post_meta( $post_id, 'yearly_interval', true );
		$yearly_weekday  = get_post_meta( $post_id, 'yearly_weekday', true );
		$yearly_doymonth = get_post_meta( $post_id, 'yearly_doymonth', true );
		$start_year      = date( 'Y', strtotime( $start_date ) );
		$end_year        = date( 'Y', strtotime( $end_date ) );
		$years           = range( $start_year, $end_year );
		$monthly_months  = stristr( $yearly_doymonth, '13' ) ? range( 1, 12 ) : [ $yearly_doymonth ];
		foreach ( $years as $year ) {
			foreach ( $monthly_months as $monthly_month ) {
				$date_obj   = DateTime::createFromFormat( '!m', $monthly_month );
				$month_name = $date_obj->format( 'F' );
				$dates []   = date( 'Y-m-d', strtotime( "$yearly_interval $yearly_weekday of $month_name $year" ) );
			}
		}

	} elseif ( $start_date == $end_date ) {
		$dates [] = $start_date;
	} else {
		$dates = ecp_get_dates_in_period( $start_date, $end_date );
	}

	if ( ! empty( $dates ) ) {
		$start_time = date( 'H:i:s', strtotime( $start_time ) );
		$end_time   = date( 'H:i:s', strtotime( $end_time ) );

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}ecp_events WHERE post_id=%d", $post_id ) );
		$values = [];
		$dates  = array_unique( $dates );
		usort( $dates, "event_calendar_pro_date_sort" );

		foreach ( $dates as $date ) {
			$values[] = $wpdb->prepare( '(%d, %s, %s, %s, %s)', $post_id, $date, $end_date, $start_time, $end_time );
		}

		$sql = "INSERT INTO {$wpdb->prefix}ecp_events (post_id, start_date, end_date, start_time, end_time) values " . implode( ', ', $values );
		$wpdb->query( $sql );
	}
}

add_action( 'event_calendar_pro_event_saved', 'event_calendar_pro_generate_events' );

function event_calendar_pro_delete_events( $post_id ) {
	if ( 'pro_event' != get_post_type( $post_id ) ) {
		return;
	}

	global $wpdb;

	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}ecp_events WHERE 'post_id' = %d", $post_id ) );
}

add_action( 'delete_post', 'event_calendar_pro_delete_events' );

function event_calendar_pro_events_columns( $columns ) {
	unset( $columns['date'] );
	$columns['start_date'] = __( 'Start Date', 'event-calendar-pro' );
	$columns['end_date']   = __( 'End Date', 'event-calendar-pro' );
	$columns['date']       = __( 'Publish Date', 'event-calendar-pro' );

	return $columns;
}

add_action( 'manage_pro_event_posts_columns', 'event_calendar_pro_events_columns' );

function event_calendar_pro_events_columns_data( $column, $post_id ) {
	switch ( $column ) {
		case 'start_date':
			echo ecp_get_event_meta( $post_id, 'startdate' );
			break;
		case 'end_date':
			echo ecp_get_event_meta( $post_id, 'enddate' );
			break;
	}
}

add_action( 'manage_pro_event_posts_custom_column', 'event_calendar_pro_events_columns_data', 10, 2 );

/**
 * Get Countries
 */

function ecp_get_countries() {
	$countries = unserialize( get_option( 'countries' ) );
	if ( empty( $countries ) ) {
		return false;
	}

	return $countries;
}

/**
 * Get States
 */

function ecp_get_states() {
	$states = unserialize( get_option( 'states' ) );
	if ( empty( $states ) ) {
		return false;
	}

	return $states;
}

/**
 * Get Event Location
 */

function ecp_get_event_location( $post_id ) {
	$location = get_post_meta( $post_id, 'location', true );

	if ( empty( $location ) ) {
		return false;
	}

	return $location;

}

/**
 * Get Event Recurrence message
 */

function ecp_get_recurrence_message( $post_id ) {
	$eventtype = ecp_get_event_meta( $post_id, 'eventtype' );

	if ( $eventtype == 'once' ) {
		return false;
	}

	$recurtype        = ecp_get_event_meta( $post_id, 'recurtype' );
	$dailytype        = ecp_get_event_meta( $post_id, 'dailytype' );
	$daily_ndays      = ecp_get_event_meta( $post_id, 'daily_ndays' );
	$weekly_nweeks    = ecp_get_event_meta( $post_id, 'weekly_nweeks' );
	$weekly_weekdays  = explode( ',', ecp_get_event_meta( $post_id, 'weekly_weekdays' ) );
	$recurmonthly     = ecp_get_event_meta( $post_id, 'recurmonthly' );
	$monthly_dom      = ecp_get_event_meta( $post_id, 'monthly_dom' );
	$monthly_nmonths  = ecp_get_event_meta( $post_id, 'monthly_nmonths' );
	$monthly_interval = explode( ',', ecp_get_event_meta( $post_id, 'monthly_interval' ) );
	$monthly_weekdays = explode( ',', ecp_get_event_meta( $post_id, 'monthly_weekdays' ) );
	$monthly_months   = ecp_num_to_name( explode( ',', ecp_get_event_meta( $post_id, 'monthly_months' ) ) );
	$recuryearly      = ecp_get_event_meta( $post_id, 'recuryearly' );
	$yearly_day       = ecp_get_event_meta( $post_id, 'yearly_day' );
	$yearly_dommonth  = ecp_num_to_name( array( ecp_get_event_meta( $post_id, 'yearly_dommonth' ) ) );
	$yearly_dommonth  = empty( $yearly_dommonth ) ? '' : $yearly_dommonth[0];
	$yearly_interval  = ecp_get_event_meta( $post_id, 'yearly_interval' );
	$yearly_weekday   = ecp_get_event_meta( $post_id, 'yearly_weekday' );
	$yearly_doymonth  = ecp_num_to_name( array( ecp_get_event_meta( $post_id, 'yearly_doymonth' ) ) );
	$yearly_doymonth  = empty( $yearly_doymonth ) ? '' : $yearly_doymonth[0];

	ob_start();

	?>

	<p class="recurrence">
		<img src="<?php echo WPECP_ASSETS_URL . '/images/icons/reccurrence.svg' ?>" alt="" height="26" width="31"> This event occurs <?php echo $recurtype ?>,
		<?php

		if ( $recurtype == 'daily' ) {
			if ( $dailytype == 'daily' ) {
				echo " every $daily_ndays days(s)";
			} elseif ( $dailytype == 'weekdays' ) {
				echo " every weekdays";
			}
		} elseif ( $recurtype == 'weekly' ) {
			echo apply_filters( 'ecp_recurring_event_weekly_msg', "$weekly_nweeks weeks on " . join( ', ', $weekly_weekdays ), $weekly_nweeks, $weekly_weekdays );
		} elseif ( $recurtype == 'monthly' ) {
			if ( $recurmonthly == 'dom' ) {
				echo "on day $monthly_dom of every $monthly_nmonths months(s)";
			} elseif ( $recurmonthly == 'nday' ) {
				echo "on the " . join( ', ', $monthly_interval ) . " " . join( ', ', $monthly_weekdays ) . " of " . join( ', ', $monthly_months );
			}
		} elseif ( $recurtype == 'yearly' ) {
			if ( $recuryearly == 'nday' ) {
				echo "every $yearly_day day of $yearly_dommonth";
			} elseif ( $recuryearly == 'doy' ) {
				echo "the $yearly_interval $yearly_weekday of $yearly_doymonth";
			}
		}

		?>
	</p>

	<?php

	$html = ob_get_clean();

	return $html;

}

/**
 * Get Event Full Address
 */

function ecp_get_full_address( $post_id ) {

	$address  = ecp_get_event_meta( $post_id, 'address' );
	$address2 = ecp_get_event_meta( $post_id, 'address2' );
	$city     = ecp_get_event_meta( $post_id, 'city' );
	$state    = ecp_get_event_meta( $post_id, 'state' );
	$country  = ecp_get_event_meta( $post_id, 'country' );
	$zip      = ecp_get_event_meta( $post_id, 'zip' );

	$full_address = $address . $address2 . $city . $state . $country . $zip;

	return $full_address;
}

/**
 * Check the input date valid or not
 *
 * @param $date
 *
 * @return bool
 */
function ecp_is_valid_date( $date ) {
	$date_parts = explode( '-', $date );

	return checkdate( $date_parts[1], $date_parts[2], $date_parts[0] );
}
