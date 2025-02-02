=== Ripple ===

Contributors: claborier
Tags: related content, hierarchical related content, semantic related content, hierarchical, semantic, related post, breadcrumbs, seo siloing, SEO silos, silos structure
Requires PHP: 7.0
Requires at least: 4.6
Tested up to: 5.2
Stable tag: 1.5.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generate more traffic and time spent on your website by inviting users to read similar contents.
Spread your own content or even those of your partner !
Organize your content : create silos, organize your page tree and use Ripple to boost your SEO performance by using the "breadcrumbs" widget and the two "related content" ones.

== Description ==

= Ripple Dashboard =

Ripple widgets works under certain conditions which are determined by your Wordpress post types configuration.
That's why Ripple offer you an easy way to make the necessary changes on your post types so you can use Ripple the best way.

In the dashboard you will be able to customize your post types in several ways.

Global configuration of your post type :

* Make a post type hierarchical or not
=> It will allow you to use the "hierarchical related content" widget on this post type

Configure the taxonomies of your post type

* Activate/deactivate the WP core taxonomies ("category", "post_tag") for every post type
* Activate/deactivate the custom taxonomies for custom post type
=> It will give you more possibility when using the semantic related content widget

Configure your post types with Ripple specific custom fields :

* Activate/deactivate the usage of the "external url" field that allow you to publish content form the outside World !
* ... more custom fields to come ;)

Register support of Wordpress feature for your post types :

* Activate/deactivate the support of the "Excerpt" field. This field may be used by Ripple widgets.


= Ripple semantic related contents =

Increase time spent and viewed pages by unique visitors offering related and relevant similar contents below each articles. Related contents are chosen thanks to an integrated and intelligent algorithm which selects and orders items by relevance score. The way contents items are displayed is fully customizable :

* Chose a theme or customize your own CSS
* Customize your headlines in order to catch more attention and more clicks
* You can choose to display thumbnail and/or excerpt with the headline for each content item
* Define the maximum related content items that can be displayed
* Customize the display of the related content by customizing some HTML elements
* Manage the 'rel' attribute of each link generated by the widget
* Choose between the "automatic display" to display the widget right after the post content or use the shortcode to display it wherever you want

= Ripple hierarchical related contents =

This widget allow you to display the children of the current page in the same way the "semantic" widget. It allows you to offer a better navigation experiment to the user

* Customize your headlines in order to catch more attention and more clicks
* You can chose to display thumbnail and/or excerpt with the headline for each content item
* Choose between the automatic display to display the widget right after the post content or use the shortcode to display it whereever you want

Note : this widget only works for hierarchical post type.

= Ripple breadcrumbs =

This widget allow you to display the navigation breadcrumbs on hierarchical content

* Customize your headlines
* Choose the separator between every breadcrumbs link
* Choose to display the home link or not
* Choose between the automatic display to display the widget right after the post content or use the shortcode to display it wherever you want

Notice that the breadcrumb is SEO friendly as it is build using Breadcrumbs markup
More information here about markups here : https://developers.google.com/search/docs/data-types/breadcrumb

Note : this widget only works for hierarchical post type.

= Plug and play, easy to customize, and advanced settings =

This plugin is plug and play with optimized default settings for user experience. Several settings offer you the ability to customize its behaviour and the widget’s look & feels in order to fit with your needs.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. That is it ! Go to the new 'Ripple' admin menu to configure and enjoy the plugin !

== Frequently Asked Questions ==

= Why can’t I see any related content under my posts ? =

To display related content, Ripple will search for post with identical taxonomies : for example tags and categories.

Ripple will take in account every existing taxonomies existing inside your website, so feel free to abuse of them !

= How does Ripple ranks related contents ? =

Ripple defines the relevance of each related content based on matching taxonomies. The more a post have identical taxonomies with another post, the more its relevancy will be high !

If several posts have the same relevancy, then the more recent post will be considered as the more relevant.

== Screenshots ==

2. screenshot-01.png
2. screenshot-02.png
2. screenshot-03.png

== Changelog ==

= 1.5.5 =

Adding the 'clickable_item' option for related content widgets

= 1.5.4 =

Removing support for social share widget

= 1.5.3 =

New features available for the related content widgets :

* You can choose the HTML tag that wrap every text link generated by the widget.
* You can define a value for the "rel" attribute of each link generated by the widget

* Ripple offer the ability to activate the 'excerpt' field for each post type directly from the dashboard. The excerpt field can be used by Ripple Widgets as a description for a post.

= 1.5.2 =

New configuration available in the Dashboard :

* Ripple offer the ability to activate the 'excerpt' field for each post type directly from the dashboard. The excerpt field can be used by Ripple Widgets as a description for a post.

= 1.5.1 =

New configuration available in the Dashboard :

* It's now possible to activate an option that will automatically add a custom field on post types chosen by the user.
=> This custom field allow to add an "external URL" to the post type, that will be used as a permalink when Ripple spread it.

Ripple semantic related content :

* The search_base option as evolved to accept the "none" value. As a result, the widget will request for content without including any taxonomies criteria.

= 1.5.0 =

New parameters for the semantic widget shortcode !

* search_base : allow you to perform a search based on the taxonomies of the current post, a specific one or even on a custom query string (ex : search_base="category=my_category")
* post_type : by default the widget search related content of the same post type as the displayed one. You can bypass this behaviour by specifying every post type you want to search for
* tax_white_list : by default, related content are searched based on every (valid) taxonomies. You can bypass this behaviour and specify which taxonomies the widget should use to perform its search.

= 1.4.0 =

* Breadcrumbs become SEO friendly thanks to microdata attributes
* Dashboard incoming : it allows you to activate / deactivate taxonomies (tags and categories) for the different post type of your configuration. You can also make them hierarchical or not.
* Improving performance : better management of assets loading

= 1.3.0 =

New widget : Ripple breadcrumbs

* Creating the widget that allow to display a breadcrumbs
* The widget can be customize with some option (choosing the separator, display the home link, ...)
* Allow to display the widget automatically right before the content of the current page
* Adding a shortcode to display the breadcrumbs manually


New widget : Ripple hierarchical related content

* Creating the widget that allow to display the children page of the current page
* Allow to display the widget automatically if the option is activated
* Adding a shortcode to display the widget manually

Widget semantic related content

* Adding a shortocde to display the widget manually
* Allow to activate / deactivate the widget


= 1.2.2 =

Social sharing

* Fixing "email" and "pinterest" sharing tools

= 1.2.1 =

Social sharing

* Fixing a bug preventing to share the page when using the social network buttons (the popup windows remained empty)

= 1.2.0 =

Social Sharing

* Adding the possibility to choose the social networks that will appear in the social bar
* Adding "pinterest" social network

= 1.1.1 =

Related content

* Fixing bug related to "Custom CSS" admin options : the CSS was not inserted on the front end.

= 1.1.0 =

Related content

* Better management of the excerpt : the displayed excerpt is either based on the 'more tag' content, the excerpt field or the first paragraph found in the post content.
* Adding an option to manage the excerpt maximum length
* Improving the UI to manage the "Custom CSS" field
* Adding an option to display the related content within columns (from 1 to 4)
* Adding a option to choose the CSS grid system to manage column display. Possible options are "classic" or "flex"

= 1.0.0 =

Related content

* Adding an option to personalize the title of the related content box
* Adding the possibility to choose the HTML tag used to display the title
* Adding an option to choose the maximum related content to display in the related content box
* Giving the possibility to display the thumbnail of the related posts
* Giving the possibility to display the excerpt of the related posts
* Adding an option to choose between a basic CSS theme or custom CSS (fully editable by the user)

Ripple social share

* Adding the possibility to activate two social widgets on post pages : social share inside post & sidebar social share
* Adding the possibility to choose the display theme for the widgets

* Sidebar : adding an option to choose the sidebar position (left or right). Note: on mobile device, the social bar will be displayed at the bottom of the screen
* Sidebar : adding an option to choose the social bar size

* Inside post : adding an option to personalize the title displayed with the social bar
* Inside post : adding the possibility to choose the HTML tag used to display the title
* Inside post : giving the possibility to display the social bar before the post content
* Inside post : giving the possibility to display the social bar after the post content
* Inside post : adding an option to choose between two display mode
* Inside post : adding an option to choose the social bar size
* Inside post : giving the possibility to display the social share counter. The counter take in account Facebook, Linkedin and StumbleUpon

== Upgrade Notice ==

= 1.0.0 =

Initial release

= 1.1.0 =

Improving Ripple related content

= 1.1.1 =

Related content bug fix

= 1.2.0 =

New features available for Ripple social sharing : you can now choose which social network to use. Plus, "Pinterest" is now available !