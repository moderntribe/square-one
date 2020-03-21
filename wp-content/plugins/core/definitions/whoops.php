<?php
declare( strict_types=1 );

use Whoops\Handler\PrettyPageHandler;

return [
	\Whoops\Run::class => DI\autowire()
		->constructor( null )
		->method( 'pushHandler', DI\get( PrettyPageHandler::class ) ),
];
