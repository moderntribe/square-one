<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Templates;
use Tribe\Project\Twig\Extension;

class Twig_Service_Provider implements ServiceProviderInterface {
	public function register( Container $container ) {
		$container[ 'twig.loader' ] = function ( Container $container ) {
			$stylesheet_path = get_stylesheet_directory();
			$template_path   = get_template_directory();
			$loader          = new \Twig_Loader_Filesystem( [ $stylesheet_path ] );
			if ( $template_path !== $stylesheet_path ) {
				$loader->addPath( $template_path );
			}

			return $loader;
		};

		$container[ 'twig.cache' ] = function ( Container $container ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				return false;
			}

			return WP_CONTENT_DIR . '/cache/twig';
		};

		$container[ 'twig.options' ] = function ( Container $container ) {
			return apply_filters( 'tribe/project/twig/options', [
				'debug'      => WP_DEBUG,
				'cache'      => $container[ 'twig.cache' ],
				'autoescape' => false,
			] );
		};

		$container[ 'twig.extension' ] = function ( Container $container ) {
			return new Extension();
		};

		$container[ 'twig' ] = function ( Container $container ) {
			$twig = new \Twig_Environment( $container[ 'twig.loader' ], $container[ 'twig.options' ] );
			$twig->addExtension( $container[ 'twig.extension' ] );

			return $twig;
		};

		add_filter( 'tribe/project/twig', function ( $twig ) use ( $container ) {
			return $container[ 'twig' ];
		}, 0, 1 );

		$this->load_templates( $container );
	}

	private function load_templates( Container $container ) {
		$container[ 'twig.templates.content/panels/panel' ] = function ( Container $container ) {
			return new Templates\Content\Panels\Panel( 'content/panels/panel.twig', $container[ 'twig' ] );
		};
		$container[ 'twig.templates.content/panels/my-top-resources' ] = function ( Container $container ) {
			return new Templates\Content\Panels\My_Top_Resources( 'content/panels/my-top-resources.twig', $container[ 'twig' ], $container[ 'settings.resources' ] );
		};
		$container[ 'twig.templates.content/panels/resource-list' ] = function ( Container $container ) {
			return new Templates\Content\Panels\Resource_List( 'content/panels/resource-list.twig', $container[ 'twig' ], $container[ 'settings.resources' ] );
		};
	}

}