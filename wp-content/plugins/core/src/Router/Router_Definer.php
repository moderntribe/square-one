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

	protected $controllers = [
		IndexController::class,
		SingleController::class,
	];

	/**
	 * @return array
	 */
	public function define(): array {
		return [
			self::CONTROLLERS => DI\add( $this->controllers ),
			Router::class     => DI\create( Router::class )->constructor( DI\get( Handler::class ), $this->get_instantiated_controllers() ),
		];
	}

	public function get_instantiated_controllers() {
		$controllers = $this->controllers;
		$controllers = apply_filters( 'tribe/project/controllers/registered_controllers', $controllers );
		$inst        = [];

		foreach ( $controllers as $classname ) {
			$basename          = ( new \ReflectionClass( $classname ) )->getShortName();
			$inst[ $basename ] = DI\get( $classname );
		}

		return $inst;
	}

}
