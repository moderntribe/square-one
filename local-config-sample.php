<?php
/*
 * This is a sample local-config.php file
 *
 * You may include settings here that you only want
 * enabled on your local development checkouts
*/

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
define( 'WP_CACHE', false );

/*
 * Enable CSS_VERSION_TIMESTAMP if you are doing front end dev on css to force cache invalidation without running a full build
 */
// define( 'CSS_VERSION_TIMESTAMP', true );

define( 'TRIBE_DISABLE_PANELS_CACHE', true );

/*
 * Whoops
 *
 * If you enable Whoops, the Whoops error library will be used to provide better/prettier error logging.
 */
define( 'WHOOPS_ENABLE', true );

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

//define( 'TRIBE_GLOMAR', false );
