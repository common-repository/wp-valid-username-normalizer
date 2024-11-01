=== wp-valid-username-normalizer ===

* Contributors: alfreddatakillen
* Tags: wordpress, username, sanitize, normalize
* Requires at least: 3.0
* Tested up to: 3.4.2
* Stable tag: 1.0
* License: GPLv3

This plugin will clean up the mess when your user database is full av invalid usernames.

== Description ==

Wordpress functions for creating new users does not check wheather the usernames are valid (for having in URL:s etc.) So, if you have a wordpress site with some plugin that creates users with "funny characters" (such as spaces, national characters, etc.), this plugin will clean up the mess. (Yes, this is a real world scenario that did happen.)

This plugin will rename all your user's usernames to something valid, still allowing your users to continue logging in with their old invalid username, so you do not have to instruct all your users to login in with the new valid username. This plugin will map old invalid usernames to the new valid ones.

Overall, what is considered a "valid" username is a bit of a mess in Wordpress. You would expect the sanitize_user() function to return a username that works, but it does accept spaces, wildcard and at (@), which all will fuck up your authors' archives URLs in Wordpress. However, when creating new users in the wordpress admin, those characters are NOT valid. So, wordpress itself is not consistent on what is valid.

This plugin will only accept lower case a-z and 0-9. All other usernames will be rewritten.

The plugin also handles potential username conflicts, so you do not have to worry about dupes in the database after installing this plugin.

== Installation ==

1. Put the plugin in the Wordpress `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. If you have a lot of invalid users (like hundreds or thousands, then the first requests to your server after activating this plugin might take some time). You might have to reload a couple of times if your site is timing out. However, when the fist batch is converted, this will not happen again.

== Frequently Asked Questions ==

= Will this plugin work in Wordpress MU environments? =

Yes. It works great on MU!

= Where do I report bugs? = 

Report any issues at the Google Code issue tracker:
https://code.google.com/p/wp-valid-username-normalizer/issues/list

= What about the license? =

Read more about GPLv3 here:
http://www.gnu.org/licenses/gpl-3.0.html

= Do you like beer? =

If we meet some day, and you think this stuff is worth it, you may buy
me a beer in return. (GPLv3 still applies.)

== Changelog ==

= 1.0 =

*	The first public release.

