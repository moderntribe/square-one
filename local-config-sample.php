<?php
/*
 * This is a sample local-config.php file
 *
 * You may include settings here that you only want
 * enabled on your local development checkouts
 *
 * The default WordPress databases defines are automatically populated in
 * dev/docker/docker-compose.yml which you can override here with custom
 * defines e.g. define( 'DB_NAME', 'tribe_square1' );
*/

/*
 * React dev
 */
define( 'HMR_DEV', false );

/*
 * Enable ASSET_VERSION_TIMESTAMP if you are doing front end dev on css/js to force cache invalidation without running a full build
 */
define( 'ASSET_VERSION_TIMESTAMP', true );

/*
 * Whoops
 *
 * If you enable Whoops, the Whoops error library will be used to provide better/prettier error logging.
 */
define( 'WHOOPS_ENABLE', true );

/*
 * Glomar
 *
 * GLOMAR is a plugin that blocks the frontend of the site from public access.
 * If you would like to disable the plugin locally, add the following to your local-config.php.
 */
define( 'TRIBE_GLOMAR', false );
