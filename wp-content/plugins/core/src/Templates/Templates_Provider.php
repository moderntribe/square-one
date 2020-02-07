<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Psr\Container\ContainerInterface;

class Templates_Provider {

	public function register( ContainerInterface $container ): void {
		require_once( dirname( __DIR__, 2 ) . '/functions/templates.php' );

		$this->locator( $container );
	}

	private function locator( ContainerInterface $container ): void {
		add_filter( 'tribe/template/controller', function ( $controller, $path ) use ( $container ) {
			try {
				return $container->get( Template_Locator::class )->locate( $path );
			} catch ( Template_Not_Found_Exception $e ) {
				return $controller;
			}
		}, 10, 2 );
	}
}
