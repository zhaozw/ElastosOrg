=== Plugin Name ===
Contributors: ericmann
Donate link: http://www.jumping-duck.com/wordpress/
Tags: user level, role, registration, redirect, rewrite, permalink, cookie
Requires at least: 2.8
Tested up to: 3.1
Stable Tag: 1.2.1
License: GPLv2+

Set an alternate registration URL for a subset of users who need a different role on registration.

== Description ==

READ THE FAQ BEFORE UPGRADING TO VERSION 1.1!!!

You can add a special link through which users can register and have a particular user level/role applied by default.

Need to separate people into multiple categories?  Now you can automate the filtering process and save yourself time.

== Installation ==

1. Upload the entire `/reglevel` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the Reglevel Settings menu to set your redirects and map the appropriate user levels.

== Frequently Asked Questions ==

= Can I create additional user types? =

We strongly recommend you use the [Role Manager plugin](http://sourceforge.net/projects/role-manager) for WordPress.

= I used previous Beta versions, will my redirects still work? =

THIS VERSION DISCONTINUES SUPPORT OF THE LEGACY SHORTCODE OPTION!!!  PLEASE UPDATE YOUR SITE TO USE THE MORE ACCURATE, SAFER REDIRECT METHOD!!!
While previous versions of this plugin were completely backwards-compatable, we've discontinued support of our outdated shourtcode method.  The new redirect method is far more secure, browser friendly, and far less prone to error.

If you're still using pages with shortcodes, you'll have to update your system before upgrading to RegLevel 1.1.

= Will this be the last version of RegLevel? =

Nope.  There are several plugins currently on the market that could benefit from the features RegLevel offers.  We're trying to touch base with other developers so we can make our various plugins compatable.  If you are or know a developer who wants to work on future versions of RegLevel, please contact us at [Jumping Duck Media](http://www.jumping-duck.com/).

= Why can't I edit my redirects? =

In a future version we plan to allow the editing and re-tasking of redirects.  For now, if you need to change the user level attributed to a particular redirect, simply delete and recreate it.

= I want to add a user forum to my blog as well.  Does RegLevel work with BBPress? =

We're still working on that ...

== Changelog ==

= 1.2.1 =
* Removes insecure Elliot RPC integration

= 1.2.0 =

* Upgraded for compatibility with WP 3.0
* Added support for advanced error reporting

= 1.1.2 =

* Changed minimum WordPress version requirement to 2.8.0 to prevent missing function conflicts

= 1.1.1 =

* Fixes admin typo that prevented redirects from being saved to the database

= 1.1 =

* Discontinues shortcode support!
* Delete redirects from the RegLevel admin page
* Fixes Register Plus interaction error
* Cleans up installation and database manipulation
* Pulls in other registration-related WordPress options

= 1.0 =

* Adds URL query string for redirection
* Uses cookies to track redirects rather than WordPress options

= 0.3 =

* Fixes "Headers already sent" error

= 0.2 =

* Introduces shortcodes for managing multiple redirects

= 0.1 =

* Initial release

== Upgrade Notice ==

= 1.2.1 =
Fixes security vulnerability. Upgrade immediately.