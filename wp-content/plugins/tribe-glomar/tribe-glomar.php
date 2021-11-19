<?php declare(strict_types=1);

/**
* Plugin Name: Tribe Glomar
* Description: Force the frontend of the site to hide if you are not logged in.
* Author: Modern Tribe, Inc.
* Author URI: http://tri.be
* Version: 1.0.5
* License: GPL v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Requires PHP: 7.4
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */

use Tribe\Plugin\Tribe_Glomar;
use Tribe\Plugin\Tribe_Glomar_Admin;

if ( ! function_exists( 'tribe_glomar_load' ) ) { // play nice
	function tribe_glomar_load(): void {
		if ( defined( 'TRIBE_GLOMAR' ) && TRIBE_GLOMAR === false ) {
			return;
		}

		if ( ! apply_filters( 'tribe_glomar', true ) ) {
			return;
		}

		// ok, we have permission to load
		require_once 'Tribe_Glomar.php';
		require_once 'Tribe_Glomar_Admin.php';
		Tribe_Glomar::init( new Tribe_Glomar_Admin() );
	}

	add_action( 'plugins_loaded', 'tribe_glomar_load' );
}
