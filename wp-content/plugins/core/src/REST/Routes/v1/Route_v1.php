<?php
/**
 * Version 1 base class.
 *
 * Not necessary for a single version API, but left in as an example of what your v2 would look like.
 *
 * @package Square1-REST
 */
declare( strict_types=1 );

namespace Tribe\Project\REST\Routes\v1;

use Tribe\Project\REST\Routes\Route;

/**
 * Class Route_v1.
 */
abstract class Route_v1 extends Route {

	// Version.
	protected $version = 'v1';
}
