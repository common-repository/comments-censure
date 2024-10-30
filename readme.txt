=== Comments Censure ===
Contributors: gVectors Team
Tags: comment, Commenting, comments, spam, protection, unwanted comments
Requires at least: 3.5.0
Tested up to: 4.9
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Comments Censure - Keep your blog clean from uncensored comments.

== Description ==

Comments Censure filters uncensored comments before showing them to end-users.
If you want to keep your blog clean, then this plugin is just for you. 
Comments Censure is very flexible and has many settings, which allows you to manage censoring process as you want, 
disallow or replacing uncensored words with pre-defined words. 
All you must do, is installing plugin and forget about uncensored comments forever, 
the plugin will take care about your blog comments.

* Comments Censure is RTL and Multisite ready!

= Comments Censure: =

* Importing uncensored words from .txt file
* Imports default words in wordpress current language (file name should look like words-en_US.txt)
* Allows to add uncensored words manually on settings page
* Replaces uncensored words (custom or global replacement)
* Filtering email content
* Export/Import options
* Export/Import search/replace combinations
* New uncensored comment notifications via email

= Comments Censure PRO: =

* Image replacement for each search word with manageable width / height settings
* Actions when uncensored word detected in comment
       * Ignore
       * Pre-submit validation and restriction
       * Pending moderation
       * Set as Spam
       * Move to Trashed
* Actions when external URL detected in comment
       * Ignore
       * Pre-submit validation and restriction
       * Pending moderation
       * Set as Spam
       * Move to Trashed
* Block users if comment contains uncensored words for X days
* Block users if comment contains external urls for X days
* Blocked users list page with detailed information
* Whitelisted external domains (for comments with external urls)
* Hard block users by IP addresses
* Hard block users by EMAIL addresses
* Front end phrases for translations

* Upgrade to Comments Censure PRO: http://gvectors.com/product/comments-censure-pro/

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/comments-censure` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Comments Censure screen to configure the plugin

== Screenshots ==

1. Comments with replaced uncensored words | Screenshot #1
2. Settings words tab | Screenshot #2
3. Settings words tab - search words | Screenshot #3
4. Settings main tab - main settings | Screenshot #4
5. Settings phrases tab | Screenshot #5
6. Settings export tab | Screenshot #6
7. Settings import tab | Screenshot #7

== Frequently Asked Questions ==

= Does this plugin works with all comments plugins/themes? =

Yes! The plugin supports all plugins and themes if the plugin/theme coded with wordpress standards.

= Uncensored words not replacing =

Reason: if the plugin or theme you use doesn't call wordpress `comment_text` hook

Comment text hook: <https://codex.wordpress.org/Function_Reference/comment_text>

== Changelog ==

= 1.0.2 =
* Fixed Bug : words per page option saving issue

= 1.0.1 =
* Added option: New uncensored comment notifications via email

= 1.0.0 =
Initial version