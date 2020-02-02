<?php
/*
 * This is a sample tests-config.php file
 * In it, you *must* include the four main database defines
 *
 * You may include other settings here that you only want
 * enabled on your test development checkouts
 *
 * Generally speaking, this file could be very similar to your local-config.php,
 * with the exception of the database credentials and caching.
*/

define( 'DB_NAME', 'tribe_square1_tests' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_HOST', 'mysql' );

/*
 * React dev
 */

define( 'HMR_DEV', false );

/*
 * Debugging
 */

define( 'WP_DEBUG', true );
define( 'SAVEQUERIES', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
define( 'WP_CACHE', true );

/*
 * Define a dedicated cache salt for tests to better isolate them
 * from object caches from other types of request.
 *
 * If you're using WPDb for direct database manipulation, the
 * cache invalidation mechanism might be compromised and you might
 * face unexpected results in tests that are hard to debug.
 *
 * If you'd like to disable object caching completely during tests:
 * 1 - Replace the line below with "define( 'WP_CACHE_KEY_SALT', md5( microtime( true ) ) );"
 * 2 - Call "if ( tribe_is_codeception() ) wp_suspend_cache_addition( true );" on a mu-plugin
 *
 * Generally speaking, it's a good idea to run tests with cache enabled
 * because they run faster and you get to test your cache invalidation logic
 */
define( 'WP_CACHE_KEY_SALT', 'tests' );

$GLOBALS['memcached_servers'] = [ [ 'memcached', 11211, ] ];

/*
 * Whoops
 *
 * If you enable Whoops, the Whoops error library will be used to provide better/prettier error logging.
 */
define( 'WHOOPS_ENABLE', false );

/*
 * Multisite
 *
 * If you enable multisite in wp-config.php, ensure to provide your local.tribe URI here
 */
//define( 'DOMAIN_CURRENT_SITE', 'square1.tribe' );

/*
 * Glomar
 *
 * GLOMAR is a plugin that blocks the frontend of the site from public access.
 * If you would like to disable the plugin locally, add the following to your local-config.php.
 */

define( 'TRIBE_GLOMAR', false );
