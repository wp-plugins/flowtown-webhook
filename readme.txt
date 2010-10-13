=== Flowtown Webhook ===
Contributors: kynatro, dtelepathy
Donate link: http://www.dtelepathy.com/
Tags: dtelepathy, flowtown, webhook, api, user, registration, register, user registration, kynatro
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: trunk

Automatically send a user to a Flowtown group when they sign up

== Description ==

Automatically send a user to a Flowtown group when they sign up and optionally when a commenter on your blog makes a comment.

== Installation ==

The plugin is simple to install:

1. Download `flowtown-webhook.zip`
1. Unzip
1. Upload `flowtown-webhook` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin

== Screenshots ==
1. Simple setup configuration. Just copy and paste your group's webhook URL and handshake key in the settings fields and hit *Save Changes*.

== Frequently Asked Questions ==
**Who created this plugin?**
[digital-telepathy](http://www.dtelepathy.com) created this plugin in an effort to integrate Flowtown with some of their products. digital-telepathy is in no way associated with Flowtown.

**Will the comment addition work with comment plugins like Disqus and Intense Debate?**
Yes, but with some caveats. There should be no problem with Intense Debate since it syncs comments in real time with your blog's database. Disqus will only sync comments with your blog's database when requested, but once the sync has been requested, the blog commenters will be added.

== Upgrade Notice ==

= 1.2 =
* Added ability to only send approved commenters to Flowtown

= 1.1 =
* Ability to add commenters to your Flowtown account added

== Changelog ==

= 1.2 =
* Added ability to only send approved commenters to Flowtown (spam commenters will always be filtered out). This is a user specifiable option.

= 1.1 =
* Added ability to send blog commenters to Flowtown

= 1.0 = 
* Initial release