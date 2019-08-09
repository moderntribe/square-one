<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops_Provider extends Service_Provider {

	const WHOOPS              = 'whoops';
	const WHOOPS_PAGE_HANDLER = 'whoops.page_handler';

	public function register( Container $container ) {

		$container[ self::WHOOPS ] = function ( $container ) {
			return new Run();
		};

		$container[ self::WHOOPS_PAGE_HANDLER ] = function ( $container ) {
			return new PrettyPageHandler();
		};

		add_action( 'init', function () use ( $container ) {
			$container[ self::WHOOPS ]->pushHandler( $container[ self::WHOOPS_PAGE_HANDLER ] );
			$container[ self::WHOOPS ]->register();
		}, - 10, 0 );
	}
}
