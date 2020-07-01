<?php
declare( strict_types=1 );

namespace Tribe\Project\Router;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Components\Handler;
use Tribe\Project\Controllers\IndexController;
use Tribe\Project\Controllers\SingleController;

/**
 * Class Router_Definer
 *
 * @package Tribe\Project\Router
 */
class Router_Definer implements Definer_Interface {

	public const CONTROLLERS = 'controllers';

	/**
	 * @return array
	 */
	public function define(): array {
		return [
			self::CONTROLLERS => [
				IndexController::class,
				SingleController::class,
			],
			Router::class => DI\create( Router::class )
				->constructor( DI\get( Handler::class ), DI\get( self::CONTROLLERS ) ),
		];
	}

}
