=== Metaphor Members ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FUZKZGAJSBAE6
Tags: custom post type, members, team, team members, member info, info
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: /trunk/
License: GPL2

Creates a custom post type to display info about members of your group or organization.

== Description ==

**This is not a membership plugin.**

Create individual posts to display information about the members or your organization. Includes a shortcode to generate a grid based archive of your members.

The Member post type includes the following fields:

* Basic content editor
* Featured image
* Member title
* *Contact info - Unlimited title/desciption fields
* *Social links - Unlimited list of social site icons/links
* *Social links target
* *Twitter handle - Add the member's Twitter handle

*Use these fields in conjuction with **[Metaphor Widgets](http://wordpress.org/extend/plugins/mtphr-widgets/)** to display this info on each single Member post sidebar.

#### Member Archive Shortcode

**Attributes**
* **posts_per_age** - Set the number of members to display per page. *Default: 9*.
* **columns** - Set the number of columns in the grid. *Default: 3*.
* **excerpt_length** - The length of the post excerpt. This will max out at the set excerpt length of your theme. *Default: 80*.
* **excerpt_more** - The display of the 'more' link of the excerpt. Wrap text in curly brackets to create a permalink to the post. *Default: &hellip*.
* **assets** - Set the order of the archive post assets. Set as a string with assets separated by commas. Available assets are: **thumbnail** **name** **social** **title** **excerpt**. *Default: thumbnail,name,social,title,excerpt*.

**Shortcode Examples**

`[mtphr_members_archive]`

`[mtphr_members_archive posts_per_page="6" columns="4" excerpt_length="200" excerpt_more="{View info}" assets="thumbnail,name,excerpt"]`



== Installation ==

1. Upload `mtphr-members` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where is the documentation =

Documentation is coming soon!

== Screenshots ==

1. Coming soon.

== Changelog ==

= 1.0.3 =
* Added member category taxonomy.
* Updated archive shortcode to filter by categories.
* Updated Metaphor Widget Overrides to remove unused widgets.

= 1.0.2 =
* Updated css classes for responsive and non-responsive site.
* Added filter to set responsiveness.

= 1.0.1 =
* Added respond.js to add media queries for older browsers.

= 1.0.0 =
* Initial upload of Metaphor Members.

== Upgrade Notice ==

= 1.0.3 =
Added member category taxonomy. Updated archive shortcode to filter by categories. Updated Metaphor Widget Overrides to remove unused widgets.

= 1.0.2 =
Updated css classes for responsive and non-responsive site. Added filter to set responsiveness.

= 1.0.1 =
Added respond.js to add media queries for older browsers.

= 1.0.0 =
Initial upload of Metaphor Members.

