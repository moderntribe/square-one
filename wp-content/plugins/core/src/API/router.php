<?php
/**
 * Dispatches calls to the Router.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API;

use Tribe\Project\API\Controllers\Example_Controller;
use Tribe\Project\API\Router\Route_Factory;
use Tribe\Project\API\Router\Router;

/**
 * Because this is loaded outside of WP, we need to include our local config and Composer dependencies manually.
 */
require_once '../../../local-config.php';
require_once './vendor/autoload.php';

// Create our router.
$route_factory = new Route_Factory();
$router         = new Router(
	'so',
	'v1',
	$route_factory
);

/**
 * Add Routes.
 */
$router->add_route( Router::READABLE, 'example[/]', new Example_Controller() );

/**
 * Dispatch current route
 */
$router->dispatch();

exit;
