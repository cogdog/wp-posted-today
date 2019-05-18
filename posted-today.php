<?php
/*
Plugin Name: Posted Today
Plugin URI: https://github.com/cogdog/wp-posted-today
Description: Shortcode [postedtoday] to generate a list of posts from previous year on the same month and day as today.
Version: 0.3
License: GPLv2
Author: Alan Levine
Author URI: https://cog.dog
Text Domain: postedtoday
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

add_action( 'init', 'cdb_postedtoday_shortcode' );

function cdb_postedtoday_shortcode() {
		add_shortcode( 'postedtoday' , 'cdb_postedtoday' );
}
 

function cdb_postedtoday( $atts ) {

	extract(shortcode_atts( array( "month" => '', "day" => '', 'excerpt' => 1 ), $atts ));

	// yep, today is today, load with current month, day, uear
	$today = array(
		'mon' => date( 'n', current_time( 'timestamp', 0 ) ),
		'mday' => date( 'j', current_time( 'timestamp', 0 ) ),
		'year' => date( 'Y', current_time( 'timestamp', 0 ) )
	);
	
	// check if we got a valid month as a parameter to use instead of this month
	if ( !empty( $month) and $month > 0 and $month < 13) {
		$today['mon'] = $month;
	}
	
	// check if we got a valid day as a parameter to use instead of today
	if ( !empty( $day) and $day > 0 and $day < 32) {
		$today['mday'] = $day;
	}
	
	// construct query for post with today's date and month, but also before today
	$posts_from_today = new WP_Query( array(
		'post_type' => 'post',
		'date_query' => array(
			array(
				'month' => $today['mon'],
				'day'   => $today['mday'],
				'before' => array (
						 'month' => $today['mon'],
						 'day' => $today['mday'],
						 'year' => $today['year'], 
						)
			),
		),
		'posts_per_page' => -1,
	) );
	
	// so we can match by year
	$year_tracker = $today['year'];
	
	// a flag for the first entry
	$first_year = true;

	// gor results?
	if ( $posts_from_today->have_posts() ) {
		
		$the_date = date_i18n('F j', strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $today['year']   ));
		// get the grammar right for a result of 1
		$singular = sprintf(
			_x('There is <strong>1</strong> post previously published on %s', 'Single post found', 'postedtoday'),
			$the_date
		);
		$multiple = sprintf(
			_x('There are <strong>%c</strong> posts previously published on %s', 'Multiple posts found', 'postedtoday'),
			$posts_from_today->found_posts,
			$the_date
		);
		$intro = ($posts_from_today->found_posts == 1) ? $singular : $multiple;
	
		// summary of results
		$output = sprintf(
			'<p>%s</p><ul class="todaypost">',
			$intro
		);
		
		while( $posts_from_today->have_posts() ) {
			$posts_from_today->the_post();
			
			// get the year of post
			$post_year = get_the_date('Y', $posts_from_today->post->ID );
			
			if ( $post_year != $year_tracker ) {
				// we have a new year to work with
				
				// check if first  year to show in list; if no we need to end previous list
				if ( $first_year ) {
					$endlist = '';
					$first_year = false;
				} else {
					$endlist = '</ul></li>';
				}
				
				// ok make the list for this year
				$output .= $endlist . '<li><strong>' . date_i18n('F j', strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $post_year   )) . '</strong><ul>';
				
				// now track this year as current
				$year_tracker = $post_year;
				
			}
			
			// output post and link
			$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a>';
			// display excerpt if we want it
			if ( $excerpt ) $output .= ' <span class="today_excerpt">' . get_the_excerpt() .   '</span>';
			
			$output .= '</li></ul>';
			
		} // while $posts_from_today
		
		$output .= '</ul></li></ul>';
		
		
	} else {
		$output = '<p>' . sprintf(
			_x('No posts were previously published on %s', 'No posts for this date', 'postedtoday'),
			date_i18n('F j', strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $today['year']   ) )
		) . '</p>';	
	}
	
	// restore post query
	wp_reset_postdata();
	
	return $output;
	
}
?>