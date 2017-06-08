<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Templates;
use Tribe\Project\Twig\Extension;

class Twig_Service_Provider implements ServiceProviderInterface {
	public function register( Container $container ) {
		require_once( dirname( $container['plugin_file'] ) . '/functions/components.php' );

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

		$this->set_component_contexts();
	}

	private function load_templates( Container $container ) {
		$container[ 'twig.templates.content/panels/panel' ] = function ( Container $container ) {
			return new Templates\Content\Panels\Panel( 'content/panels/panel.twig', $container[ 'twig' ] );
		};
	}

	/**
	 * Updates the current component context so that any loaded component can be context-aware.
	 */
	protected function set_component_contexts() {
		add_action( 'wp_head', function () {
			add_filter( 'component_context', function () {
				return 'body';
			} );
		}, 999 );

		add_action( 'wp_footer', function () {
			add_filter( 'component_context', function () {
				return 'footer';
			} );
		} );

		add_action( 'dynamic_sidebar_before', function () {
			add_filter( 'component_context', function () {
				return 'sidebar';
			} );
		} );

		add_action( 'dynamic_sidebar_after', function () {
			add_filter( 'component_context', function () {
				return 'body';
			} );
		} );
	}

}