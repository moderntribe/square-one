=== Tribe 301 Redirects ===
Contributors: scottnelle, modern tribe
Tags: 301, redirect, url, seo
Requires at least: 1.5
Tested up to: 3.0
Stable tag: 1.03

Simple 301 Redirects provides an easy method of redirecting requests to another page on your site or elsewhere on the web.

== Description ==

Simple 301 Redirects provides an easy method of redirecting requests to another page on your site or elsewhere on the web. It's especially handy when you migrate a site to WordPress and can't preserve your URL structure. By setting up 301 redirects from your old pages to your new pages, any incoming links will be seemlessly passed along, and their pagerank (or what-have-you) will be passed along with them.

Note: The format for requests is '/about.htm' and the format for redirects is 'http://www.domain.com/about/' in order to emulate the way Apache handles 301 redirects and because while you can only accept requests to your site, you can redirect to anywhere.

== Installation ==

1. Upload Tribe 301 Redirects to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add redirects on the Settings > 301 Redirects page.

== Screenshots ==

1. The admin interface

== Changelog ==

= 1.03 =
* Sorry for the double update. I forgot to check for PHP4 compatibility. Many people are still using PHP4, apparently, so this update is to fix compatibility with these legacy systems.


= 1.02 =
* Added support for special characters in non-english URLs.
* Fixed a case sensitivity bug.


= 1.01 =
* Updated redirect method to send headers directly rather than using wp_redirect() because it was sending 302 codes on some servers

= 1.0 =
* Initial Release
