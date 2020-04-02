<?php
declare( strict_types=1 );

namespace Tribe\Project\Development;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Whoops\Handler\PrettyPageHandler;

class Whoops_Definer implements Definer_Interface {
	public function define(): array {
		return [
			\Whoops\Run::class => DI\autowire()
				->constructor( null )
				->method( 'pushHandler', DI\get( PrettyPageHandler::class ) ),
		];
	}

}
