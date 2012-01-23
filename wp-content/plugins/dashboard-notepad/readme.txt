=== Dashboard Notepad ===
Contributors: sillybean
Tags: widget, dashboard, notes
Donate Link: http://sillybean.net/code/wordpress/dashboard-notepad/
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 1.39

The very simplest of notepads for your Dashboard. 

== Description ==

This dashboard widget provides a simple notepad. The widget settings allow you to choose which roles can edit the notes, and which roles can merely read them. Version 1.30 also adds support for custom roles and integrates with the <a href="http://wordpress.org/extend/plugins/members/">Members plugin</a> for role settings.

You can display the contents of your notepad using a template tag and/or shortcode. The widget permissions apply to these tags as well: only users with permission to read the notes will see the notes on the front end. You can use `div#dashboard-notes` in your theme's CSS file to style the notes.

= Translations =

* Belorussian (be_BY) by <a href="http://fatcow.com">FatCow</a>.
* Bulgarian (bg_BG) by <a href="http://www.siteground.com/">SiteGround</a>.
* German (de_DE) by Guido Kerkewitz
* Swedish (se_SV) by <a href="http://www.rabatt.se">Rabatt</a>

== Translations ==

If you would like to send me a translation, please write to me through <a href="http://sillybean.net/about/contact/">my contact page</a>. Let me know which plugin you've translated and how you would like to be credited. I will write you back so you can attach the files in your reply.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to your Dashboard and configure the widget by clicking the link in its upper right corner.
1. To display your notes in a theme file, use the `<?php dashboard_notes(); ?>` template tag.
1. To display your notes in a post, page, or text widget, use the `[dashboard_notes]` shortcode. (To use it in a widget, you'll have to enable shortcode parsing in text widgets, if you haven't already. Add `add_filter('widget_text', 'do_shortcode');` to your functions.php file.) You can use `div#dashboard-notes` in your theme's CSS file to style the notes.

== Screenshots ==

1. The notepad
1. The widget options

== Upgrade Notice ==

= 1.39 =
* Fixed a bug in which users' permissions could not be removed once given. If this has affected you, reset the roles by saving the widget options once more after you have upgraded to this version.

== Changelog ==

= 1.39 =
* Fixed a bug in which users' permissions could not be removed once given.
= 1.38 =
* Swedish (se_SV) translation by <a href="http://www.rabatt.se">Rabatt</a>
* Corrected German translation.
= 1.37 =
* German (de_DE) translation by Guido Kerkewitz.
= 1.36 =
* Added an option to set the height of the notepad.
= 1.35 =
* Fixed a bug where submitting the form redirected you to the wrong site when used in a multsite/subdirectory installation.
= 1.34 =
* Bulgarian (bg_BG) translation by <a href="http://www.siteground.com/">SiteGround</a>.
= 1.33 =
* Fixed a problem where the widget would not appear for some users after upgrading from 1.24.
= 1.32 =
* Fixed a problem with the widget options that could cause a couple of error messages to appear on some servers.
= 1.31 =
* This version should seamlessly update the role options from the previous versions.
= 1.30 =
* Fixed the roles to work correctly, added support for custom roles, and added <a href="http://wordpress.org/extend/plugins/members/">Members</a> integration. (December 15, 2010)
= 1.24 =
* Belorussian (be_BY) translation by <a href="http://fatcow.com">FatCow</a>. (November 22, 2009)
= 1.23 =
* Publicly displayed notes are now surrounded by a div tag with an ID ('dashboard-notes') for styling (November 18, 2009)
= 1.22 =
* Fixed bug where the dashboard widget disappeared when unregistered users were allowed to read the notes. (November 17, 2009)
= 1.21 =
* Added option to allow the public (unregistered users) read the notes. (November 16, 2009)
= 1.2 =
* New template tag and shortcode to display notes publicly.
* Security fix, as a result of the new tags: now checking whether users can post unfiltered HTML in the notes.
* Added translation support.
* Fixed CSS bug that threw off column widths. (November 14, 2009)
= 1.1 =
* Fixed bug in the role configuration.
* The widget now disappears entirely for users who aren't allowed to read its contents. (August 24, 2009)
= 1.0 =
* First release (August 5, 2009)