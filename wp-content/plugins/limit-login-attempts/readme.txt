=== Limit Login Attempts ===
Contributors: johanee
Tags: login, security, authentication
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.7.1

Limit rate of login attempts for each IP. Also support additional security for password reset, rate limit on new user registrations, and more.

== Description ==

THIS IS A BETA VERSION!

Additional security features for many parts of user handling: login, signup, password reset and more.

Limit the number of login attempts possible both through normal login as well as using auth cookies.

By default WordPress allows unlimited login attempts either through the login page or by sending special cookies. This allows passwords (or hashes) to be brute-force cracked with relative ease.

Limit Login Attempts blocks an Internet address from making further attempts after a specified limit on retries is reached, making a brute-force attack difficult or impossible.

** TODO!!

Additional security features for many parts of user handling: login, signup, password reset and more.

The plugin also help you protect user login names from discovery. This includes password reset attempts for privileged users, rate limit on new user registrations. TODO: spam accounts

Features

* Limit the number of retry attempts when logging in for each IP. Fully customizable
* Optional logging and email notification
* Handles attempts to log in using auth cookies
* Show remaining retries or lockout time on login page
* Optional restrictions of password resets for privileged users
* Optional rate limit of new user registration
* Handles server behind reverse proxy
* Help protect user login names from discovery (work in progress)

Translations: Bulgarian, Brazilian Portuguese, Catalan, Chinese (Traditional), Czech, Dutch, French, Finnish, German, Hungarian, Norwegian, Persian, Romanian, Russian, Spanish, Swedish, Turkish. (Most translations not yet updated to plugin version 2.)

Plugin uses standard actions and filters only.

== Installation ==

1. Download and extract plugin files to a folder in your wp-content/plugin directory.
2. Activate the plugin through the WordPress admin interface.
3. Customize the settings from the options page, if desired. If your server is located behind a reverse proxy make sure to change this setting.

If you have any questions or problems please make a post here: http://wordpress.org/tags/limit-login-attempts

== Frequently Asked Questions ==

= Why not reset failed attempts on a successful login? =

This is very much by design. Otherwise you could brute force the "admin" password by logging in as your own user every 4th attempt.

= What is this option about site connection and reverse proxy? =

A reverse proxy is a server in between the site and the Internet (perhaps handling caching or load-balancing). This makes getting the correct client IP to block slightly more complicated.

The option default to NOT being behind a proxy -- which should be by far the common case.

= How do I know if my site is behind a reverse proxy? =

You probably are not or you would know. We show a pretty good guess on the option page. Set the option using this unless you are sure you know better.

= I locked myself out testing this thing, what do I do? =

Either wait, or:

If you have ftp / ssh access to the site rename the file `wp-content/plugins/limit-login-attempts/limit-login-attempts.php` to deactivate the plugin.

If you have access to the database (for example through phpMyAdmin) you can clear the `limit_login_lockouts` option in the wordpress options table.

Don't do this unless you know what you are doing.

In a default setup this would work: `UPDATE wp_options SET option_value = '' WHERE option_name = 'limit_login_lockouts'`

= I disabled password reset for administrators and forgot my password, what do I do? =

If you have ftp / ssh access look at the answer regarding being locked out above to disable plugin. Reset password before re-enabling plugin.

If you have access to the database (for example through phpMyAdmin) you can remove the plugin options value. This will revert settings to default values which allow password reset using account e-mail (for privileged users).

Plugin options are stored in `limit_login_options` option in the wordpress options table. You can remove this in a default setup using: `DELETE FROM wp_options WHERE option_name = 'limit_login_options'`. PLEASE BE CAREFUL OR YOU MIGHT SCREW UP YOUR WORDPRESS INSTALL!

Truly advanced users can edit the 'disable_pwd_reset' entry in the serialized array.

Plugin options are stored in `limit_login_options` option in the wordpress options table. You can remove this in a default setup using: `DELETE FROM wp_options WHERE option_name = 'limit_login_options'`. PLEASE BE CAREFUL OR YOU WILL SCREW UP YOUR WORDPRESS INSTALL!

Truly advanced users can edit the 'disable_pwd_reset' entry in the serialized array of course.

== Screenshots ==

1. Loginscreen after failed login with retries remaining
2. Loginscreen during lockout
3. New user registration screen during lockout
4. Administration interface in WordPress 2.8.4
5. Administration interface in WordPress 2.5

== Todo ==

* grep TODO

* re-do without using user levels
* escape all translated strings

* Re-re-check: user login name protection, track nonempty_credentials

* make dashboard text better

* show when old translation

* TEST TEST TEST TEST

* Translations
* Look through readme.txt again

* Update screenshots
* Update site

== Change Log ==

= Version 2.0beta4 =
* Better plugin WordPress integration
* Code re-organization. Split into multiple files
* Improved option handling & better upgrade from previous version
* Make cookie handling optional again for now -- some people have reported problems with it in 1.5.1
* Only autoload the necessary option table entries
* Log time of last lockout for each IP in log; keep track of last increase + last clear for statistics
* Forward-merged changes from versions 1.5 - 1.6.2
* Move translations to separate directory
* Updated Swedish translation
* Updated Bulgarian translation, thanks to Hristo Chakarov
* Updated Spanish translation, thanks to Marcelo Pedra
* Added Brazilian Portugese translation, thanks to Gervásio
* Plugin localization strings changed again unfortunately...
* Removed user nicename editor for now. It is a lot of work to get working safely for everyone, and I need to wrap up release for version 2. Hopefully it'll be back later.

= Version 2.0beta3 =
* Checkpoint release for translations
* Added basic functionality to edit user nicenames
* Added Wordpress version dependency for password reset functionality
* Code clean-ups

= Version 2.0beta2 =
* Many various fixes and improvements! (sorry, no detailed notes)

= Version 2.0beta1 =
* Added a number of options that when activated make it harder to find login names of users
* disable password reset using username (accept user email only) for users with a specified role or higher
* disable password reset for users with a specified role or higher
* restrict rate of new user registrations
* filter registration error messages to avoid possible way to brute force find user login name
* list of privileged users show which login names can be discovered from user displayname, nickname or "url name"/nicename

= 1.6.2 =
* Fix bug where log would not get updated after it had been cleared
* Do plugin setup in 'init' action
* Small update to Spanish translation file, thanks to Marcelo Pedra
* Tested against WordPress 3.2.1

= 1.6.1 =
* (WordPress 3.0+) An invalid cookie can sometimes get sent multiple times before it gets cleared, resulting in multiple failed attempts or even a lockout from a single invalid cookie. Store the latest failed cookie to make sure we only count it as one failed attempt
* Define "Text Domain" correctly
* Include correct Dutch tranlation file. Thanks to Martin1 for noticing. Thanks again to Bjorn Wijers for the translation
* Updated POT file for this version
* Tested against WordPress 3.1-RC4

= 1.6.0 =
* Happy New Year
* Tested against WordPress 3.1-RC1
* Plugin now requires WordPress version 2.8+. Of course you should never ever use anything but the latest version
* Fixed deprecation warnings that had been piling up with the old version requirement. Thanks to Johannes Ruthenberg for the report that prompted this
* Removed auth cookie admin check for version 2.7.
* Make sure relevant values in $_COOKIE get cleared right away on auth cookie validation failure. There are still some problems with cookie auth handling. The lockout can trigger prematurely in rare cases, but fixing it is plugin version 2 stuff unfortunately.
* Changed default time for retries to reset from 24 hours to 12 hours. The security impact is very minor and it means the warning will disappear "overnight"
* Added question to FAQ ("Why not reset failed attempts on a successful login?")
* Updated screenshots

= 1.5.2 =
* Reverted minor cookie-handling cleanup which might somehow be responsible for recently reported cookie related lockouts
* Added version 1.x Brazilian Portuguese translation, thanks to Luciano Passuello
* Added Finnish translation, thanks to Ari Kontiainen

= 1.5.1 =
* Further multisite & WPMU support (again thanks to <erik@erikshosting.com>)
* Better error handling if option variables are damaged
* Added Traditional Chinese translation, thanks to Denny Huang <bigexplorations@bigexplorations.com.tw>

= 1.5 =
* Tested against WordPress 3.0
* Handle 3.0 login page failure "shake"
* Basic multisite support (parts thanks to <erik@erikshosting.com>)
* Added Dutch translation, thanks to Bjorn Wijers <burobjorn@burobjorn.nl>
* Added Hungarian translation, thanks to Bálint Vereskuti <balint@vereskuti.info>
* Added French translation, thanks to oVa <ova13lastar@gmail.com>

= Version 1.4.1 =
* Added Turkish translation, thanks to Yazan Canarkadas

= Version 1.4 =
* Protect admin page update using wp_nonce
* Added Czech translation, thanks to Jakub Jedelsky

= Version 1.3.2 =
* Added Bulgarian translation, thanks to Hristo Chakarov
* Added Norwegian translation, thanks to Rune Gulbrandsøy
* Added Spanish translation, thanks to Marcelo Pedra
* Added Persian translation, thanks to Mostafa Soufi
* Added Russian translation, thanks to Jack Leonid (http://studio-xl.com)

= Version 1.3.1 =
* Added Catalan translation, thanks to Robert Buj
* Added Romanian translation, thanks to Robert Tudor

= Version 1.3 =
* Support for getting the correct IP for clients while server is behind reverse proxy, thanks to Michael Skerwiderski
* Added German translation, thanks to Michael Skerwiderski

= Version 1.2 =
* No longer replaces pluggable function when cookie handling active. Re-implemented using available actions and filters
* Filter error messages during login to avoid information leak regarding available usernames
* Do not show retries or lockout messages except for login (registration, lost password pages). No change in actual enforcement
* Slightly more aggressive in trimming old retries data

= Version 1.1 =
* Added translation support
* Added Swedish translation
* During lockout, filter out all other login errors
* Minor cleanups

= Version 1.0 =
* Initial release

== Upgrade Notice ==

= 1.7.1 =
Users of version 1.6.2 and 1.7.0 should upgrade immediately. There was a problem with "auth cookie" lockout enforcement. Lockout of normal password login attempts still worked as it should. Please see plugin Changelog for more information.
