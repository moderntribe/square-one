<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Initializer;
use Tribe\Project\Blog_Copier\Network_Admin_Screen;

class Blog_Copier_Provider implements ServiceProviderInterface {
	const ADMIN_SCREEN = 'blog_copier.admin_screen';
	const INITIALIZER  = 'blog_copier.initializer';

	public function register( Container $container ) {
		$container[ self::ADMIN_SCREEN ] = function ( Container $container ) {
			return new Network_Admin_Screen();
		};

		add_action( 'network_admin_menu', function () use ( $container ) {
			$container[ self::ADMIN_SCREEN ]->register_screen();
		}, 10, 0 );
		add_action( 'network_admin_edit_' . Network_Admin_Screen::NAME, function () use ( $container ) {
			$container[ self::ADMIN_SCREEN ]->handle_submission();
		}, 10, 0 );

		$container[ self::INITIALIZER ] = function ( Container $container ) {
			return new Copy_Initializer( $container[ Queues_Provider::DEFAULT_QUEUE ] );
		};

		add_action( 'tribe/project/copy-blog/copy', function( Copy_Configuration $configuration ) use ( $container ) {
			$container[ self::INITIALIZER ]->initialize( $configuration );
		}, 10, 1 );
	}
}