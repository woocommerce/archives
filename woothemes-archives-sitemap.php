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
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	);

	$args = wp_parse_args( $args, $defaults );

	// Allow child themes/plugins to filter here.
	$args = apply_filters( 'woothemes_archives_sitemap_args', $args );
	$html = '';

	do_action( 'woothemes_archives_sitemap_before', $args );

		// Begin output.
		if ( 0 < count( $args['post_types'] ) ) {
			foreach ( $args['post_types'] as $k => $v ) {
				$data = get_posts( array( 'posts_per_page' => -1, 'post_type' => esc_attr( $v ) ) );
				if ( 0 < count( $data ) ) {
					// Retrieve data about the post type.
					$post_type_obj = get_post_type_object( $v );
					$html .= '<div id="sitemap-' . esc_attr( $k ) . '">' . "\n";
					if ( isset( $post_type_obj->labels->name ) ) {
						$html .= '<h3>' . $post_type_obj->labels->name . '</h3>' . "\n";
					}
					$html .= '<ul>' . "\n";

					switch ( $v ) {
						case 'page':
							$html .= wp_list_pages( 'depth=0&sort_column=menu_order&title_li=&echo=0' );
						break;

						default:
							foreach ( $data as $i => $post ) {
								setup_postdata( $post );
								$html .= '<li><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . get_the_title() . '</a></li>' . "\n";
							}
						break;
					}

					$html .= '</ul>' . "\n" . '</div><!--/#sitemap-' . esc_attr( $k ) . '-->';
				}
			}
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

	return woothemes_archives_sitemap( $args );
} // End woothemes_archives_sitemap_shortcode()
}

add_shortcode( 'woothemes_archives_sitemap', 'woothemes_archives_sitemap_shortcode' );
?>