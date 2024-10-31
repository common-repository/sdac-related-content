=== SDAC Related Content ===
Tags: related, related posts, related by category, related by tags, admin panel, template, wordpress-mu
Contributors: jenz
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 2.3.1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4761649

== Description ==
The SDAC Related Content plugin uses caching to output the highly configurable results after your content on single post pages.  This plugin works without making any additional new tables in your database.

== Installation ==
1. Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload
the top-level folder. Don't just upload all the php files and put them in `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the Related Posts Options (in Settings Menu)

There are two ways to add the related posts to your content:
1. Use the admin to automatically add the plugin to your content.
2. Put this code into the WordPress loop: 
`
<?php if (function_exists('sdac_related_content_by_type')) { sdac_related_content_by_type(); } ?>
`
Enjoy!

== Frequently Asked Questions ==

= Why are no posts showing up under "Related Posts" on my single post pages?
Make sure you have "echo" set to "Yes" so that the posts will show up.

= Where can I get support for this plugin?
You can submit any issues/feedback: http://www.sandboxdev.com/forums/forum/sdac-wordpress-plugins/sdac-related-content/

== Screenshots ==

1. General Options
2. Styling Options
3. Content Options

== Changelog ==

 = 2.3.1 =
* (October 11, 2011)
* Added a div to surround output for better control of layout (sdac_related_posts)

 = 2.3 =
* (June 23, 2010)

* Moved to using register_settings() (requires WordPress 2.8
* Complete code cleanup

 = 2.1.1 =
* (July 8, 2009)

* Moved the settings to the "Settings" menu (best practice)
* Changed the actual plugin file name to be more consistent

 = 2.1 =
* (May 20, 2009)

* Added in new functionality to show posts by tag or by category
* Fixed an issue where no posts would show up with default settings
* Changed main function from sdac_related_content_by_category to sdac_related_content_by_type

 = 2.0.2 =
* (April 21, 2009)

* Fixed default settings (echo, post type)
* Added FAQ section to README

 = 2.0.1 =
* (April 20, 2009)

* Replaced "Related Posts" text with "Related Content"
* Updated README
* Added Screenshots

 = 2.0 =
* (April 15, 2009)
* Re-written to include an easy to use Admin panel
* Added in the option to automatically include it after the_content()

 = 1.0 =
* Released with bare bones functionality

== Upgrade Notice ==

= 2.3.1 =
Added a div to surround output for better control of layout (sdac_related_posts)

 = 2.3 =
Major code cleanup, overhaul. This update requires WordPress 2.8 or newer.


