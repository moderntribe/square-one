<?php
declare( strict_types=1 );

namespace Tribe\Project\Router;

use Tribe\Libs\Container\Definer_Interface;

class Router_Definer implements Definer_Interface {
	public const ROUTER = 'router';
	public const ROUTES = 'routes';

	public function define(): array {
		return [
			self::ROUTER => function () {
				return new Router();
			},
			self::ROUTES => function() {
				return new Routes();
			}
		];
	}

}
