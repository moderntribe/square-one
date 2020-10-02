<?php
declare( strict_types=1 );

namespace Tribe\Project\Routes;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Routes\Route_Definer;

class Routes_Definer implements Definer_Interface {
	public function define() : array {
		return [
			\Tribe\Libs\Routes\Route_Definer::ROUTES => DI\add( [
				DI\get( Sample_Route::class ),
			] ),
			\Tribe\Libs\Routes\Route_Definer::REST_ROUTES => DI\add( [
				DI\get( Sample_REST_Route::class ),
			] ),
		];
	}
}
