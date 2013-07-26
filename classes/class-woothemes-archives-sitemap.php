<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * WooThemes Archives Sitemap Class
 *
 * All functionality pertaining to the Sitemap feature.
 *
 * @package WordPress
 * @subpackage Woothemes_Archives
 * @category Plugin
 * @author Matty
 * @since 1.0.0
 */
class Woothemes_Archives_Sitemap {
	/**
	 * Get posts by type.
	 * @access  private
	 * @since   1.0.0
	 * @param   string $post_type The post type to get posts of.
	 * @return  array             Array of post objects.
	 */
	private function _get_posts_by_type ( $post_type ) {
		if ( empty( $post_type ) ) return new WP_Error( 'empty_post_type', __( 'No post type specified. Please specify a post type token.', 'woothemes-archives' ) );

		$args = array( 'posts_per_page' => -1, 'post_type' => esc_attr( $post_type ) );

		if ( $this->_is_woocommerce_activated() && 'product' == $post_type ) {
			$args['meta_query'] = array( array(
									'key' => '_visibility',
									'value' => array( 'catalog', 'visible' ),
									'compare' => 'IN'
								) );
		}

		$data = get_posts( $args );

		return $data;
	} // End _get_posts_by_type()

	/**
	 * Render the HTML for a list of the posts of a given post type.
	 * @access  public
	 * @since   1.0.0
	 * @param   string $post_type The post type to render HTML for.
	 * @return  string            Rendered HTML unordered list.
	 */
	public function render_posts_html ( $post_type, $args ) {
		global $post;
		if ( empty( $post_type ) ) return new WP_Error( 'empty_post_type', __( 'No post type specified. Please specify a post type token.', 'woothemes-archives' ) );

		$html = '';
		switch ( $post_type ) {
			case 'page':
				$html .= wp_list_pages( 'depth=0&sort_column=menu_order&title_li=&echo=0' );
			break;

			default:
				$data = $this->_get_posts_by_type( $post_type );

				if ( 0 < count( $data ) ) {
					// Retrieve data about the post type.
					$post_type_obj = get_post_type_object( $post_type );
					$html .= '<div id="sitemap-' . esc_attr( $post_type ) . '">' . "\n";
					if ( isset( $post_type_obj->labels->name ) ) {
						$html .= $args['before_title'] . $post_type_obj->labels->name . $args['after_title'] . "\n";
					}
					$html .= '<ul>' . "\n";

					foreach ( $data as $i => $post ) {
						setup_postdata( $post );
						$html .= '<li><a href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . get_the_title() . '</a></li>' . "\n";
					}
					wp_reset_postdata();

					$html .= '</ul>' . "\n" . '</div><!--/#sitemap-' . esc_attr( $post_type ) . '-->';
				}
			break;
		}

		return $html;
	} // End render_posts_html()

	/**
	 * Render the HTML for a list of the terms of a given taxonomy.
	 * @access  public
	 * @since   1.0.0
	 * @param   string $taxonomy  The taxonomy to render HTML for.
	 * @param   array  $args      Arguments to adjust output.
	 * @return  string            Rendered HTML unordered list.
	 */
	public function render_terms_html ( $taxonomy, $args ) {
		global $post;
		if ( empty( $taxonomy ) ) return new WP_Error( 'empty_taxonomy', __( 'No taxonomy specified. Please specify a taxonomy token.', 'woothemes-archives' ) );

		$html = '';
		// Retrieve data about the taxonomy.
		$tax_obj = get_taxonomy( $taxonomy );
		$html .= '<div id="sitemap-' . esc_attr( $taxonomy ) . '">' . "\n";
		if ( isset( $tax_obj->labels->name ) ) {
			$html .= $args['before_title'] . $tax_obj->labels->name . $args['after_title'] . "\n";
		}
		$html .= '<ul>' . "\n";

		switch ( $taxonomy ) {
			default:
				$html .= wp_list_categories( 'taxonomy=' . esc_attr( $taxonomy ) . '&title_li=&hierarchical=0&show_count=1&echo=0' );
			break;
		}

		$html .= '</ul>' . "\n" . '</div><!--/#sitemap-' . esc_attr( $taxonomy ) . '-->';

		return $html;
	} // End render_terms_html()

	/**
	 * Render monthly archives HTML.
	 * @access  public
	 * @since   1.0.0
	 * @param   array  $args Arguments to adjust output.
	 * @return  string Rendered HTML unordered list inside a DIV tag.
	 */
	public function render_archives_html ( $args ) {
			$html = '<div id="sitemap-archives">' . "\n";
			$html .= $args['before_title'] . __( 'Archives', 'woothemes-archives' ) . $args['after_title'] . "\n";
			$html .= '<ul>' . "\n";
			$html .= wp_get_archives( 'type=monthly&show_post_count=1&echo=0' );
			$html .= '</ul>' . "\n";
			$html .= '</div><!--/#sitemap-archives-->' . "\n";

			return $html;
	} // End render_archives_html();

	/**
	 * Check if WooCommerce is activated.
	 * @access  private
	 * @since   1.0.0
	 * @return  boolean
	 */
	private function _is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	} // End _is_woocommerce_activated()
} // End Class
?>