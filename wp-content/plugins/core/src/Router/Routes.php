<?php

namespace Tribe\Project\Router;

class Routes {

	public function add_routes( Router $router ) {
		$router->get( '/[{page:.+}]', 'IndexController@loop' );
		// etc for other routes
	}

}
