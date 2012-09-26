=== Buddypress and qTranslate ===
Contributors: Dominik Matus
Donate link: http://dominikmatus.cz
Tags: buddypress, qtranslate, admin bar, menu,
Requires at least: 2.?.?
Tested up to: 3.0.0
Stable tag: 1.0

Plugin for optimize qTranslate for Buddypress and support of BP Admin bar

== Description ==
Plugin for optimize [qTranslate](http://wordpress.org/extend/plugins/qtranslate/) with [Buddypress](http://wordpress.org/extend/plugins/buddypress/) and support of BP Admin bar

1. Add drop down menu with qtranslate languaget to Buddypress adminbar
2. Optimize qTranslate with Buddypress (do not work, coming soon)

For full functionality, you must delete code line `add_filter(‘clean_url’, ‘qtrans_convertURL’,);` in file `qtranslate_hooks.php` in folder `plugins/qtranslate`.

== Installation ==

1. Download the plugin
2. Extract the plugin into `/wp-content/plugins/` directory
3. Activate the plugin, delete code

== Frequently Asked Questions ==

Nothing

== Upgrade Notice ==

nothing

== Screenshots ==

1. Menu in Admin bar

== Changelog ==

= 1.2 =
Language button changed to Lanuages
Fixed localization of "Languages" button

= 1.1 =
Added function for loading gettext from Wordpress and qTranslate files

= 1.0 =
First version of WP Chat