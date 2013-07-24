<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enable the usage of do_action( 'woothemes_archives_timeline' ) to display a timeline within a theme/plugin.
 *
 * @since  1.0.0
 */
add_action( 'woothemes_archives_timeline', 'woothemes_archives_timeline' );

if ( ! function_exists( 'woothemes_archives_timeline' ) ) {
/**
 * Display or return HTML-formatted timeline.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return string
 */
function woothemes_archives_timeline ( $args = '' ) {
	global $post, $woothemes_archives;

	$defaults = array(
		'limit' => '-1',
		'orderby' => 'menu_order',
		'order' => 'DESC',
		'id' => 0,
		'echo' => true,
		'size' => 50,
		'per_row' => 3,
		'link_title' => true,
		'title' => '',
		'before' => '<div class="woothemes_archives_timeline">',
		'after' => '</div><!--/.woothemes_archives_timeline-->',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
		'category' => 0
	);

	$args = wp_parse_args( $args, $defaults );

	// Allow child themes/plugins to filter here.
	$args = apply_filters( 'woothemes_archives_timeline_args', $args );
	$html = '';

	do_action( 'woothemes_archives_timeline_before', $args );

		// The Query.
		$query = $woothemes_archives->data->get_timeline_data( $args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			$dates_array 			= array();
			$year_array 			= array();
			$i 						= 0;
			$prev_post_ts    		= null;
			$prev_post_year  		= null;
			$distance_multiplier	=  9;

			$html .= $args['before'] . "\n";

			if ( '' != $args['title'] ) {
				$html .= $args['before_title'] . esc_html( $args['title'] ) . $args['after_title'] . "\n";
			}

			$html .= '<section id="timeline">' . "\n";

			// Begin templating logic.
			$tpl = '<span class="date">%%DATE%%</span> <span class="linked">%%TITLE%%</span> <span class="comments">%%COMMENTS%%</span>';
			$tpl = apply_filters( 'woothemes_archives_timeline_item_template', $tpl, $args );

			$i = 0;
			foreach ( $query as $post ) {
				setup_postdata( $post );
				$post_ts    =  strtotime( $post->post_date );
				$post_year  =  date( 'Y', $post_ts );
				$template = $tpl;
				$i++;

				$image_size = apply_filters( 'woothemes_archives_timeline_image_size', 'thumbnail', $post );

				$image = get_the_post_thumbnail( $post->ID, $image_size );

				$date = get_the_date( 'F j', $post->post_date ) . '<sup>' . get_the_date( 'S', $post->post_date ) . '</sup>';

				$comments = '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' . _n( __( '1', 'woothemes-archives' ), sprintf( __( '%d', 'woothemes-archives' ), $post->comment_count ), $post->comment_count, 'woothemes-archives' ) . '</a>';

				$title = get_the_title();
				if ( true == $args['link_title'] ) {
					$image = '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( $title ) . '">' . $image . '</a>';
					$title = '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( $title ) . '">' . $title . '</a>';
				}

				// Optionally display the image, if it is available.
				if ( has_post_thumbnail() ) {
					$template = str_replace( '%%IMAGE%%', $image, $template );
				} else {
					$template = str_replace( '%%IMAGE%%', '', $template );
				}

				$template = str_replace( '%%DATE%%', $date, $template );
				$template = str_replace( '%%TITLE%%', $title, $template );
				$template = str_replace( '%%COMMENTS%%', $comments, $template );

				$template = apply_filters( 'woothemes_archives_timeline_template', $template, $post );

				/* Handle the first year as a special case */
				if ( is_null( $prev_post_year ) ) {
					$html .= '<h3 class="archive_year">' . $post_year . '</h3>' . "\n";
					$html .= '<ul class="archives_list">' . "\n";
				} else if ( $prev_post_year != $post_year ) {
					/* Close off the OL */
					$html .= '</ul>' . "\n";
					$working_year = $prev_post_year;
					/* Print year headings until we reach the post year */
					while ( $working_year > $post_year ) {
						$working_year--;
						$html .= '<h3 class="archive_year">' . $working_year . '</h3>' . "\n";
					}

					/* Open a new ordered list */
					$html .= '<ul class="archives_list">' . "\n";
				}

				/* Compute difference in days */
					if ( ! is_null( $prev_post_ts ) && $prev_post_year == $post_year ) {
						$dates_diff = ( date( 'z', $prev_post_ts ) - date( 'z', $post_ts ) ) * $distance_multiplier;
					}
					else {
						$dates_diff = 0;
					}

					$html .= '<li>' . "\n";
					$html .= $template;
					$html .= '</li>' . "\n";

					/* For subsequent iterations */
					$prev_post_ts = $post_ts;
					$prev_post_year = $post_year;
				} // End FOREACH Loop

				/* If we've processed at least *one* post, close the ordered list */
				if ( ! is_null( $prev_post_ts ) ) {
					$html .= '</ul>' . "\n";
				}


			$html .= '</section><!--/#timeline-->' . "\n";
			$html .= $args['after'] . "\n";

			wp_reset_postdata();
		}

		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woothemes_archives_timeline_html', $html, $query, $args );

		if ( $args['echo'] != true ) { return $html; }

		// Should only run is "echo" is set to true.
		echo $html;

		do_action( 'woothemes_archives_timeline_after', $args ); // Only if "echo" is set to true.
} // End woothemes_archives_timeline()
}

if ( ! function_exists( 'woothemes_archives_timeline_shortcode' ) ) {
/**
 * The shortcode function for the "Timeline" view.
 * @since  1.0.0
 * @param  array  $atts    Shortcode attributes.
 * @param  string $content If the shortcode is a wrapper, this is the content being wrapped.
 * @return string          Output using the template tag.
 */
function woothemes_archives_timeline_shortcode ( $atts, $content = null ) {
	$args = (array)$atts;

	$defaults = array(
		'limit' => '-1',
		'orderby' => 'date',
		'order' => 'DESC',
		'echo' => true,
		'size' => 50,
		'link_title' => true,
		'category' => 0
	);

	$args = shortcode_atts( $defaults, $atts );

	// Make sure we return and don't echo.
	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );
	if ( isset( $args['size'] ) &&  ( 0 < intval( $args['size'] ) ) ) $args['size'] = intval( $args['size'] );
	if ( isset( $args['category'] ) && is_numeric( $args['category'] ) ) $args['category'] = intval( $args['category'] );

	// Fix booleans.
	foreach ( array( 'link_title' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}

	return woothemes_archives_timeline( $args );
} // End woothemes_archives_timeline_shortcode()
}

add_shortcode( 'woothemes_archives_timeline', 'woothemes_archives_timeline_shortcode' );
?>