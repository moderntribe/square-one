<?php

namespace Tribe\Project\Router;

class Routes {

	public function add_routes( Router $router ) {
		$show = get_option( 'show_on_front' );

		if ( $show === 'posts' ) {
			$router->get( '/[{page:.+}]', 'IndexController@loop' );
		} else {
			$router->get( '/[{page:.+}]', 'HomeController@home' );
		}


		// etc for other routes
	}

}
