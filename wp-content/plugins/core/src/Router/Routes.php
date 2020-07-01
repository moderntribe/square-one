<?php

namespace Tribe\Project\Router;

/**
 * Class Routes
 *
 * @package Tribe\Project\Router
 */
class Routes {

	/**
	 * @param Router $router
	 */
	public function add_routes( Router $router ) {
		$show = get_option( 'show_on_front' );

		if ( $show === 'posts' ) {
			$router->get( '/', 'IndexController@loop' );
			$router->get( '/page/[{page}]', 'IndexController@loop' );
		} else {
			$router->get( '/', 'SingleController@home' );
			$router->get( '/page/[{page}]', 'SingleController@home' );
		}

		$router->get( '/{pagename}/', 'SingleController@single' );
		// etc for other routes
	}

}
