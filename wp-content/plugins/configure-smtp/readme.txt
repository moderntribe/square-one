=== Configure SMTP ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: email, smtp, gmail, sendmail, wp_mail, phpmailer, outgoing mail, tls, ssl, security, privacy, wp-phpmailer, coffee2code
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 3.1
Version: 3.1

Configure SMTP mailing in WordPress, including support for sending e-mail via SSL/TLS (such as GMail).


== Description ==

Configure SMTP mailing in WordPress, including support for sending e-mail via SSL/TLS (such as GMail).

This plugin is the renamed, rewritten, and updated version of the wpPHPMailer plugin.

Use this plugin to customize the SMTP mailing system used by default by WordPress to handle *outgoing* e-mails. It offers you the ability to specify:

* SMTP host name
* SMTP port number
* If SMTPAuth (authentication) should be used.
* SMTP username
* SMTP password
* If the SMTP connection needs to occur over ssl or tls

In addition, you can instead indicate that you wish to use GMail to handle outgoing e-mail, in which case the above settings are automatically configured to values appropriate for GMail, though you'll need to specify your GMail e-mail (including the "@gmail.com") and password.

Regardless of whether SMTP is enabled, the plugin provides you the ability to define the name and e-mail of the 'From:' field for all outgoing e-mails.

A simple test button is also available that allows you to send a test e-mail to yourself to check if sending e-mail has been properly configured for your blog.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/configure-smtp/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `configure-smtp.zip` inside the `/wp-content/plugins/` directory (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Click the plugin's `Settings` link next to its `Deactivate` link (still on the Plugins page), or click on the `Settings` -> `SMTP` link, to go to the plugin's admin settings page.  Optionally customize the settings (to configure it if the defaults aren't valid for your situation).
1. (optional) Use the built-in test to see if your blog can properly send out e-mails.


== Frequently Asked Questions ==

= I am already able to receive e-mail sent by my blog, so would I have any use or need for this plugin? =

Most likely, no.  Not unless you have a preference for having your mail sent out via a different SMTP server, such as GMail.

= How do I find out my SMTP host, and/or if I need to use SMTPAuth and what my username and password for that are? =

Check out the settings for your local e-mail program.  More than likely that is configured to use an outgoing SMTP server.  Otherwise, contact your host or someone more intimately knowledgeable about your situation.

= I've sent out a few test e-mails using the test button after having tried different values for some of the settings; how do I know which one worked? =

If your settings worked, you should receive the test e-mail at the e-mail address associated with your WordPress blog user account.  That e-mail contains a time-stamp which was reported to you by the plugin when the e-mail was sent.  If you are trying out various setting values, be sure to record what your settings were and what the time-stamp was when sending with those settings.

= Why am I getting this error when attempting to send a test message: `SMTP Error: Could not connect to SMTP host.`? =

There are a number of reasons you could be getting this error:
# Your server (or a router to which it is connected) may be blocking all outgoing SMTP traffic.
# Your mail server may be configured to allow SMTP connections only from certain servers.
# You have supplied incorrect server settings (hostname, port number, secure protocol type).

= What am I getting this error: `SMTP Error: Could not authenticate.`? =

The connection to the SMTP server was successful, but the credentials you provided (username and/or password) are not correct.


== Screenshots ==

1. A screenshot of the plugin's admin settings page.


== Changelog ==

= 3.1 =
* Add new debugging configuration option
* Fix bug that resulted from WP 3.2's update to a new phpmailer
* Fix bug with checking 'Use GMail?' did not auto-reset settings accordingly (jQuery bug regarding .attr() vs .prop() introduced in jQ 1.6 in WP 3.2)
* Fix to call add_filter() instead of add_action() for 'wp_mail_from' (props Callum Macdonald)
* Fix to call add_filter() instead of add_action() for 'wp_mail_from_name'
* Store error messages for later display rather than immediately outputting (too early)
* Save a static version of itself in class variable $instance
* Deprecate use of global variable $c2c_configure_smtp to store instance
* Add explicit empty() checks in a couple places
* Delete plugin settings on uninstallation
* Add __construct(), activation(), and uninstall()
* Add more FAQ questions
* Regenerate .pot
* Update plugin framework to version 023
* Note compatibility through WP 3.2+
* Drop compatibility with versions of WP older than 3.0
* Explicitly declare all functions as public and class variables as private
* Minor code formatting changes (spacing)
* Update copyright date (2011)
* Add plugin homepage and author links in description in readme.txt

= 3.0.1 =
* Update plugin framework to 017 to use password input field instead of text field for SMTP password

= 3.0 =
* Re-implementation by extending C2C_Plugin_016, which among other things adds support for:
    * Reset of options to default values
    * Better sanitization of input values
    * Offload of core/basic functionality to generic plugin framework
    * Additional hooks for various stages/places of plugin operation
    * Easier localization support
* Add error checking and reporting when attempting to send test e-mail
* Don't configure the mailer to use SMTP if no host is provided
* Fix localization support
* Store plugin instance in global variable, $c2c_configure_smtp, to allow for external manipulation
* Rename class from 'ConfigureSMTP' to 'c2c_ConfigureSMTP'
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor tweaks to code formatting (spacing)
* Add Upgrade Notice section to readme.txt
* Add PHPDoc documentation
* Add package info to top of file
* Update copyright date
* Remove trailing whitespace
* Update screenshot
* Update .pot file

= 2.7 =
* Fix to prevent HTML entities from appearing in the From name value in outgoing e-mails
* Added full support for localization
* Added .pot file
* Noted that overriding the From e-mail value may not take effect depending on mail server and settings, particular if SMTPAuth is used (i.e. GMail)
* Changed invocation of plugin's install function to action hooked in constructor rather than in global space
* Update object's option buffer after saving changed submitted by user
* Miscellaneous tweaks to update plugin to my current plugin conventions
* Noted compatibility with WP2.9+
* Dropped compatibility with versions of WP older than 2.8
* Updated readme.txt
* Updated copyright date

= 2.6 =
* Now show settings page JS in footer, and only on the admin settings page
* Removed hardcoded path to plugins dir
* Changed permission check
* Minor reformatting (added spaces)
* Tweaked readme.txt
* Removed compatibility with versions of WP older than 2.6
* Noted compatibility with WP 2.8+

= 2.5 =
* NEW
* Added support for GMail, including configuring the various settings to be appropriate for GMail
* Added support for SMTPSecure setting (acceptable values of '', 'ssl', or 'tls')
* Added "Settings" link next to "Activate"/"Deactivate" link next to the plugin on the admin plugin listings page
* CHANGED
* Tweaked plugin's admin options page to conform to newer WP 2.7 style
* Tweaked test e-mail subject and body
* Removed the use_smtp option since WP uses SMTP by default, the plugin can't help you if it isn't using SMTP already, and the plugin should just go ahead and apply if it is active
* Updated description, installation instructions, extended description, copyright
* Extended compatibility to WP 2.7+
* Facilitated translation of some text
* FIXED
* Fixed bug where specified wordwrap value wasn't taken into account

= 2.0 =
* Initial release after rewrite from wpPHPMailer

= pre-2.0 =
* Earlier versions of this plugin existed as my wpPHPMailer plugin, which due to the inclusion of PHPMailer within WordPress's core and necessary changes to the plugin warranted a rebranding/renaming.


== Upgrade Notice ==

= 3.1 =
Recommended update. Highlights: fixed numerous bugs; added a debug mode; updated compatibility through WP 3.2; dropped compatibility with version of WP older than 3.0; updated plugin framework.

= 3.0.1 =
Minor update.  Use password input field for SMTP password instead of regular text input field.

= 3.0 =
Recommended update! This release includes a major re-implementation, bug fixes, localization support, and more.