=== Archives by WooThemes ===
Contributors: woothemes,mattyza,jeffikus
Donate link: http://woothemes.com/
Tags: sitemap, timeline, woothemes, shortcode
Requires at least: 3.4.2
Tested up to: 3.5.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hi, we're a collection of archive functions for WordPress. Use us to display archives of your content in "Timeline" or "Sitemap" format.

== Description ==

"Archive by WooThemes" is a collection of archive functions for WordPress. Use us to display archives of your content in "Timeline" or "Sitemap" format, using either a shortcode, action or template tag.

Looking for a helping hand? [View plugin documentation](http://wordpress.org/extend/plugins/archives-by-woothemes/other_notes/).

Looking to contribute code to this plugin? [Fork the repository over at GitHub](http://github.com/woothemes/archives/).
(submit pull requests to the "develop" branch)

== Installation ==

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `<?php do_action( 'woothemes_archives_timeline' ); ?>` or `<?php do_action( 'woothemes_archives_sitemap' ); ?>` in your templates, or use the provided shortcode.

== Frequently Asked Questions ==

= The plugin looks unstyled when I activate it. Why is this? =

"Archives by WooThemes" is a lean plugin that aims to keep it's purpose as clean and clear as possible. Thus, we don't load any preset CSS styling, to allow full control over the styling within your theme or child theme.

= How do I contribute? =

We encourage everyone to contribute their ideas, thoughts and code snippets. This can be done by forking the [repository over at GitHub](http://github.com/woothemes/archives/).

= Usage =

To display your timeline via a theme or a custom plugin, please use the following code:

`<?php do_action( 'woothemes_archives_timeline' ); ?>`

To add arguments to this, please use any of the following arguments, using the syntax provided below:

* 'limit' => 5 (the maximum number of items to display)
* 'orderby' => 'menu_order' (how to order the items - accepts all default WordPress ordering options)
* 'order' => 'DESC' (the order direction)
* 'id' => 0 (display a specific item)
* 'echo' => true (whether to display or return the data - useful with the template tag)
* 'size' => 50 (the pixel dimensions of the image)
* 'per_row' => 3 (when creating rows, how many items display in a single row?)
* 'link_title' => true (link the posts's title to it's permalink)
* 'title' => '' (an optional title)
* 'before' => '&lt;div class="woothemes_archives_timeline"&gt;' (the starting HTML, wrapping the timeline)
* 'after' => '&lt;/div&gt;&lt;!--/.woothemes_archives_timeline--&gt;' (the ending HTML, wrapping the timeline)
* 'before_title' => '&lt;h2&gt;' (the starting HTML, wrapping the title)
* 'after_title' => '&lt;/h2&gt;' (the ending HTML, wrapping the title)
* 'category' => 0 (the ID/slug of the category to filter by)

The various options for the "orderby" parameter are:

* 'none'
* 'ID'
* 'author'
* 'title'
* 'date'
* 'modified'
* 'parent'
* 'rand'
* 'comment_count'
* 'menu_order'
* 'meta_value'
* 'meta_value_num'

`<?php do_action( 'woothemes_archives_timeline', array( 'limit' => 10, 'link_title' => false ) ); ?>`

The same arguments apply to the shortcode which is `[woothemes_archives_timeline]` and the template tag, which is `<?php woothemes_archives_timeline(); ?>`.


To display your sitemap via a theme or a custom plugin, please use the following code:

`<?php do_action( 'woothemes_archives_sitemap' ); ?>`

To add arguments to this, please use any of the following arguments, using the syntax provided below:

* 'limit' => 5 (the maximum number of items to display)
* 'echo' => true (whether to display or return the data - useful with the template tag)
* 'post_types' => array( 'page', 'post' ) (post type items to return)
* 'taxonomies' => array( 'category' ) (specific categories to include)
* 'show_archives' => true (include links to the archive pages)
* 'show_posts_by_category' => true (group the items by category)
* 'before' => '&lt;div class="woothemes_archives_sitemap"&gt;' (the starting HTML, wrapping the sitemap)
* 'after' => '&lt;/div&gt;&lt;!--/.woothemes_archives_sitemap--&gt;' (the ending HTML, wrapping the sitemap)
* 'before_title' => '&lt;h3&gt;' (the starting HTML, wrapping the title)
* 'after_title' => '&lt;/h3&gt;' (the ending HTML, wrapping the title)

`<?php do_action( 'woothemes_archives_sitemap', array( 'limit' => 10, 'post_types' => array( 'page', 'post', 'course' ) ) ); ?>`

The same arguments apply to the shortcode which is `[woothemes_archives_sitemap]` and the template tag, which is `<?php woothemes_archives_sitemap(); ?>`.

== Usage Examples ==

Adjusting the limit and order of the timeline, using the arguments in the three possible methods:

do_action() call:

`<?php do_action( 'woothemes_archives_timeline', array( 'limit' => 10, 'order' => "ASC" ) ); ?>`

woothemes_archives_timeline() template tag:

`<?php woothemes_archives_timeline( array( 'limit' => 10, 'order' => "ASC" ) ); ?>`

[woothemes_archives_timeline] shortcode:

`[woothemes_archives_timeline limit="10" order="ASC"]`

Adjusting the limit and post types of the sitemap to include a post type called 'course', using the arguments in the three possible methods:

do_action() call:

`<?php do_action( 'woothemes_archives_sitemap', array( 'limit' => 10, 'post_types' => array( 'page', 'post', 'course' ) ) ); ?>`

woothemes_archives_sitemap() template tag:

`<?php woothemes_archives_sitemap( array( 'limit' => 10, 'post_types' => array( 'page', 'post', 'course' ) ) ); ?>`

[woothemes_archives_sitemap] shortcode:

`[woothemes_archives_sitemap limit="10" post_types="page,post,course"]`

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =
* First release. Woo!

== Upgrade Notice ==

= 1.0.0 =
* First release. Woo!