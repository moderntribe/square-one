<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Templates;
use Tribe\Project\Twig\Extension;
use Tribe\Project\Twig\Twig_Cache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig_Service_Provider extends Service_Provider {
	const ENVIRONMENT = 'twig';
	const COMPONENT_FACTORY = 'twig.component.factory';

	public function register( Container $container ) {
		$container['twig.loader'] = function () {
			$stylesheet_path = get_stylesheet_directory();
			$template_path   = get_template_directory();
			$loader          = new FilesystemLoader( [ $stylesheet_path ] );
			if ( $template_path !== $stylesheet_path ) {
				$loader->addPath( $template_path );
			}

			return $loader;
		};

		$container['twig.cache'] = function () {
			return new Twig_Cache( WP_CONTENT_DIR . '/cache/twig/' );
		};

		$container['twig.options'] = function ( Container $container ) {
			return apply_filters( 'tribe/project/twig/options', [
				'debug'       => WP_DEBUG,
				'cache'       => $container['twig.cache'],
				'autoescape'  => false,
				'auto_reload' => true,
			] );
		};

		$container['twig.extension'] = function () {
			return new Extension();
		};

		$container['twig'] = function ( Container $container ) {
			$twig = new Environment( $container['twig.loader'], $container['twig.options'] );
			$twig->addExtension( $container['twig.extension'] );

			return $twig;
		};

		add_filter( 'tribe/project/twig', function ( $twig ) use ( $container ) {
			return $container['twig'];
		}, 0, 1 );

		$container[ self::COMPONENT_FACTORY ] = function( Container $container ) {
			return new Templates\Component_Factory( $container[ self::ENVIRONMENT ] );
		};

	}

}
