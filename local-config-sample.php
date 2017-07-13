<?php
/*
 * This is a sample local-config.php file
 * In it, you *must* include the four main database defines
 *
 * You may include other settings here that you only want
 * enabled on your local development checkouts
*/

define( 'DB_NAME', '' );
define( 'DB_USER', '' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '' );


/*
 * Debugging
 */

define( 'WP_DEBUG', true );
define( 'SAVEQUERIES', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
define( 'WP_CACHE', false );


/*
 * Glomar
 *
 * GLOMAR is a plugin that blocks the frontend of the site from public access.
 * If you would like to disable the plugin locally, add the following to your local-config.php.
 */

//define( 'TRIBE_GLOMAR', false );