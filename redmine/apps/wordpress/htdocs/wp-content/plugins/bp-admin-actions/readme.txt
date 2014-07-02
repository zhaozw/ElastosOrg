=== BP Admin Actions ===
Contributors: slaFFik
Tags: buddypress,admin,ajax,action,group,groups,manage,user,users,social,network
Tested up to: 3.0.3 and 1.2.6
Stable tag: 1.0
Requires at least: 2.9 and 1.2

Let admin do some more stuff with users - adding them to any group manually from members list

== Description ==
An "Add To Group" button will appear near Add Friend in a list of members. This button will open a popup where a site admin can select a group he wants this user become a member of.

All actions are ajaxified.

== Changelog ==

= 1.0 =
* Initial Release with the main functionality

== Installation ==
1. Upload files to /plugins/ directory
1. Activate plugin on Plugins page
1. Go to Site -> Members Directory page.
1. Add them all!

== Frequently Asked Questions ==

= Where is the button "Add To Group"? I can't see it! =
Please make sure that your theme has a code `<?php do_action('bp_directory_members_actions'); ?>` in /members/members-loop.php file.

== Screenshots ==

You can test this plugin on my demo site [GTM.Ovirium.com](http://gtm.ovirium.com/)

1. Button position
2. Popup window with group selection
