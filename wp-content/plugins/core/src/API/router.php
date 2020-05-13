<?php
/**
 * Dispatches calls to the Router.
 *
 * @package SquareOne
 * @subpackage API
 */
declare(strict_types=1);

namespace Tribe\Project\API;

use Tribe\API\Router\Router;

/**
 * Because this is loaded outside of WP, we need to include our local config and Composer dependencies manually.
 */
require_once '../../../local-config.php';
require_once '../../../vendor/autoload.php';


/**
 * Add Routes.
 *
 * @see MainController
 */
// General.
Router::get( '/api/v1/all[/]', 'MainController@results' );
Router::get( '/api/v1/all/{id:\d+}[/]', 'MainController@single_result' );
Router::get( '/api/v2/all[/]', 'MainController@results' );
Router::get( '/api/v2/all/{id:\d+}[/]', 'MainController@single_result' );

// Rulebooks.
Router::get( '/api/v1/rulebooks[/]', 'MainController@rulebooks' );
Router::get( '/api/v1/rulebooks/{id:\d+}[/]', 'MainController@single_rulebook' );

// CPT: Rules.
Router::get( '/api/v1/rules[/]', 'MainController@rules' );
Router::get( '/api/v1/rules/{id:\d+}[/]', 'MainController@single_result' );

// CPT: Regulations.
Router::get( '/api/v1/regulations[/]', 'MainController@regulations' );
Router::get( '/api/v1/regulations/{id:\d+}[/]', 'MainController@single_result' );

// CPT: Policies.
Router::get( '/api/v1/policies[/]', 'MainController@policies' );
Router::get( '/api/v1/policies/{id:\d+}[/]', 'MainController@single_result' );

// CPT: Appendices.
Router::get( '/api/v1/appendices[/]', 'MainController@appendices' );
Router::get( '/api/v1/appendices/{id:\d+}[/]', 'MainController@single_result' );

// CPT: FAQs.
Router::get( '/api/v1/faqs[/]', 'MainController@faqs' );
Router::get( '/api/v1/faqs/{id:\d+}[/]', 'MainController@single_result' );

// Updated since.
Router::get( '/api/v1/updated_since[/]', 'MainController@updated_since' );

// Object types.
Router::get( '/api/v1/objects[/]', 'MainController@objects' );

// Ads.
Router::get( '/api/v1/ads[/]', 'MainController@ads' );

// Filters
Router::get( '/api/v1/filters[/]', 'FiltersController@filters' );

// Menus
Router::get( '/api/v1/menus[/]', 'MenusController@menus' );
Router::get( '/api/v1/menus/{menu_slug}[/]', 'MenusController@single_menu' );

// Significant Changes
Router::get( '/api/v1/changes[/]', 'ChangesController@changes' );
Router::get( '/api/v1/changes/{id:\d+}[/]', 'ChangesController@single_changes' );

// Offline resources.
Router::get( '/api/v1/offline[/]', 'OfflineController@index' );
Router::get( '/api/v1/offline/{id:\d+}[/]', 'OfflineController@single' );

// Cache busting.
Router::get( '/api/v1/cache/bust[/]', 'CacheController@index' );

// Dispatch current route
Router::dispatch();
exit;
