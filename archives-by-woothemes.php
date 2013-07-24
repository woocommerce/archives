<?php
/**
 * Plugin Name: Archives
 * Plugin URI: http://woothemes.com/
 * Description: Hi, we're a collection of archive functions for WordPress. Use us to display archives of your content in "Timeline", "Sitemap" or "Grid" format, using either a shortcode, action or template tag.
 * Author: Matty Cohen | WooThemes
 * Version: 1.0.0
 * Author URI: http://woothemes.com/
 *
 * @package WordPress
 * @subpackage Woothemes_Archives
 * @author Matty
 * @since 1.0.0
 */

require_once( 'classes/class-woothemes-archives.php' );
require_once( 'classes/class-woothemes-archives-types.php' );
require_once( 'woothemes-archives-timeline.php' );
require_once( 'woothemes-archives-sitemap.php' );
global $woothemes_archives;
$woothemes_archives = new Woothemes_Archives( __FILE__ );
$woothemes_archives->version = '1.0.0';
?>