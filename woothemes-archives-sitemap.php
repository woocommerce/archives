<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enable the usage of do_action( 'woothemes_archives_sitemap' ) to display a sitemap within a theme/plugin.
 *
 * @since  1.0.0
 */
add_action( 'woothemes_archives_sitemap', 'woothemes_archives_sitemap' );

if ( ! function_exists( 'woothemes_archives_sitemap' ) ) {
/**
 * Display or return HTML-formatted sitemap.
 * @param  string/array $args  Arguments.
 * @since  1.0.0
 * @return string
 */
function woothemes_archives_sitemap ( $args = '' ) {
	global $post, $woothemes_archives;

	$defaults = array(
		'limit' => '-1',
		'echo' => true,
		'post_types' => array( 'page', 'post' ),
		'taxonomies' => array( 'category' ),
		'show_archives' => true,
		'show_posts_by_category' => true,
		'before' => '<div class="woothemes_archives_sitemap">',
		'after' => '</div><!--/.woothemes_archives_sitemap-->',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	);

	$args = wp_parse_args( $args, $defaults );

	// Allow child themes/plugins to filter here.
	$args = apply_filters( 'woothemes_archives_sitemap_args', $args );
	$html = '';

	do_action( 'woothemes_archives_sitemap_before', $args );

		// Post types output.
		if ( 0 < count( $args['post_types'] ) ) {
			foreach ( $args['post_types'] as $k => $v ) {
				$html .= $woothemes_archives->sitemap->render_posts_html( $v, $args );
			}
		}

		// Taxonomies output.
		if ( 0 < count( $args['taxonomies'] ) ) {
			foreach ( $args['taxonomies'] as $k => $v ) {
				$html .= $woothemes_archives->sitemap->render_terms_html( $v, $args );
			}
		}

		// Show archives.
		if ( true  == $args['show_archives'] ) {
			$html .= $woothemes_archives->sitemap->render_archives_html( $args );
		}

		// Show posts by category.
		if ( true  == $args['show_posts_by_category'] ) {
			$categories = get_categories( array( 'hide_empty' => true ) );
			$html .= '<div id="sitemap-posts-per-category">' . "\n";
			$html .= $args['before_title'] . __( 'Recent Posts Per Category', 'woothemes-archives' ) . $args['after_title'] . "\n";
			foreach ( $categories as $k => $v ) {
				// Retrieve latest posts.
				$posts = get_posts( array( 'cat' => intval( $v->cat_ID ), 'posts_per_page' => 10 ) );

				$html .= '<h4>' . $v->cat_name . '</h4>' . "\n";
				$html .= '<ul>' . "\n";
				if ( 0 < count( $posts ) && ! is_wp_error( $posts ) ) {
					foreach ( $posts as $i => $post ) {
						setup_postdata( $post );

						$html .= '<li><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . esc_html( get_the_title( get_the_ID() ) ) . '</a> - ' . __( 'Comments', 'woothemes-archives' ) . ' (' . get_comments_number( get_the_ID() ) . ')</li>' . "\n";
					}
					wp_reset_postdata();
				}
				$html .= '</ul>' . "\n";
			}
			$html .= '</div><!--/#sitemap-posts-per-category-->' . "\n";
		}

		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woothemes_archives_sitemap_html', $html, $args );

		if ( $args['echo'] != true ) { return $html; }

		// Should only run is "echo" is set to true.
		echo $html;

		do_action( 'woothemes_archives_sitemap_after', $args ); // Only if "echo" is set to true.
} // End woothemes_archives_sitemap()
}

if ( ! function_exists( 'woothemes_archives_sitemap_shortcode' ) ) {
/**
 * The shortcode function for the "Timeline" view.
 * @since  1.0.0
 * @param  array  $atts    Shortcode attributes.
 * @param  string $content If the shortcode is a wrapper, this is the content being wrapped.
 * @return string          Output using the template tag.
 */
function woothemes_archives_sitemap_shortcode ( $atts, $content = null ) {
	$args = (array)$atts;

	$defaults = array(
		'limit' => '-1',
		'echo' => true,
		'post_types' => array( 'page', 'post' ),
		'taxonomies' => array( 'category' ),
		'show_archives' => true,
		'show_posts_by_category' => true,
		'before' => '<div class="woothemes_archives_sitemap">',
		'after' => '</div><!--/.woothemes_archives_sitemap-->',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	);

	$args = shortcode_atts( $defaults, $atts );

	// Make sure we return and don't echo.
	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) $args['limit'] = intval( $args['limit'] );

	// Fix arrays.
	if ( isset( $args['post_types'] ) && ! is_array( $args['post_types'] ) ) $args['post_types'] = explode( ',', $args['post_types'] );
	if ( isset( $args['taxonomies'] ) && ! is_array( $args['taxonomies'] ) ) $args['taxonomies'] = explode( ',', $args['taxonomies'] );

	// Fix booleans.
	foreach ( array( 'show_archives', 'show_posts_by_category' ) as $k => $v ) {
		if ( isset( $args[$v] ) && ( 'true' == $args[$v] ) ) {
			$args[$v] = true;
		} else {
			$args[$v] = false;
		}
	}

	return woothemes_archives_sitemap( $args );
} // End woothemes_archives_sitemap_shortcode()
}

add_shortcode( 'woothemes_archives_sitemap', 'woothemes_archives_sitemap_shortcode' );
?>