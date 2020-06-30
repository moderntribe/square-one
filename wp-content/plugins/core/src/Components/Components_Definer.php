<?php
declare( strict_types=1 );

namespace Tribe\Project\Components;

use DI;
use PhpParser\ParserFactory;
use Tribe\Libs\Container\Definer_Interface;
use Twig\Environment;

class Components_Definer implements Definer_Interface {

	const PARSER = 'parser';

	public function define(): array {
		return [
			self::PARSER => function () {
				return ( new ParserFactory() )->create( ParserFactory::PREFER_PHP7 );
			},

			Handler::class => DI\create()
				->constructor( DI\get( Component_Factory::class ), DI\get( self::PARSER ) ),
		];
	}
}
