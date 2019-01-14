<?php
/*
Plugin Name: Posted Today
Plugin URI: https://github.com/cogdog/wp-posted-today
Description: Shortcode [postedtoday] to generate a list of posts from previous year on the same month and day as today.
Version: 0.1
License: GPLv2
Author: Alan Levine
Author URI: https://cog.dog
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
	
		// summary of results
		$output = '<p>There are <strong>' . $posts_from_today->found_posts . '</strong> posts found on this site published on ' . date("F j", strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $today['year']   )) . '</p> <ul class="todaypost">';
		
		while( $posts_from_today->have_posts() ) {
			$posts_from_today->the_post();
			
			// get the year of post
			$post_year = date("Y", strtotime( get_the_date() ));
			
			if ( $post_year != $year_tracker ) {
				// we have a new year to work with
				
				// check if first  year to show in list; if no we need to end previous list
				if ( $first_year ) {
					$endlist = '';
					$first_year = false;
				} else {
					$endlist = '</li></ul>';
				}
				
				// ok make the list for this year
				$output .= $endlist . '<li><strong>' . date("F j, Y", strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $post_year   )) . '</strong><ul>';
				
				// now track this year as current
				$year_tracker = $post_year;
				
			}
			
			// output post and link
			$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a>';
			// display excerpt if we want it
			if ( $excerpt ) $output .= ' <span class="today_excerpt">' . get_the_excerpt() .   '</span>';
			
			$output .= '</li>';
			
		} // while $posts_from_today
		
		$output .= '</li></ul>';
		
		
	} else {
	
		$output = 'No posts were found published on ' .  date("F j", strtotime( $today['mon'] . '/' . $today['mday'] . '/' . $today['year']   ));
	
	}
	
	// restore post query
	wp_reset_postdata();
	
	return $output;
	
}
?>