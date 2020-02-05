<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Service_Providers\Twig_Service_Provider;

class Templates_Provider extends Service_Provider {
	const LOCATOR  = 'tribe.template.locator';
	const RENDERER = 'tribe.template.renderer';

	public function register( Container $container ): void {
		require_once( dirname( $container['plugin_file'] ) . '/functions/templates.php' );

		$this->locator( $container );
		$this->renderer( $container );
		$this->templates( $container );
	}

	private function locator( Container $container ): void {
		$container[ self::LOCATOR ] = function ( Container $container ) {
			return new Template_Locator( $container );
		};
		add_filter( 'tribe/template/controller', function ( $controller, $path ) use ( $container ) {
			//return $container[ self::LOCATOR ]->locate( $path );
			return $controller;
		}, 10, 2 );
	}

	private function renderer( Container $container ): void {
		$container[ self::RENDERER ] = function ( Container $container ) {
			return new Template_Renderer( $container[ Twig_Service_Provider::ENVIRONMENT ] );
		};

		add_filter( 'tribe/template/renderer', function () use ( $container ) {
			return $container[ self::RENDERER ];
		}, 10, 0 );
	}

	private function templates( Container $container ): void {

	}
}
