<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Admin\Resources\Scripts;
use Tribe\Project\Admin\Resources\Styles;

class Admin_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$this->scripts( $container );
		$this->styles( $container );
	}

	private function scripts( Container $container ) {
		$container[ 'admin.resources.scripts' ] = function ( Container $container ) {
			return new Scripts();
		};
		add_action( 'admin_enqueue_scripts', function () use ( $container ) {
			$container[ 'admin.resources.scripts' ]->enqueue_scripts();
		}, 10, 0 );
	}

	private function styles( Container $container ) {
		$container[ 'admin.resources.styles' ] = function ( Container $container ) {
			return new Styles();
		};
		add_action( 'admin_enqueue_scripts', function () use ( $container ) {
			$container[ 'admin.resources.styles' ]->enqueue_styles();
		}, 10, 0 );
	}

}
