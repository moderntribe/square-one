<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Templates;
use Tribe\Project\Twig\Extension;
use Tribe\Project\Twig\Twig_Cache;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Twig_Service_Provider extends Service_Provider {
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

		$this->load_templates( $container );
	}

	private function load_templates( Container $container ) {
		$container['twig.templates.content/panels/panel'] = function ( Container $container ) {
			return new Templates\Content\Panels\Panel( 'content/panels/panel.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/accordion'] = function ( Container $container ) {
			return new Templates\Content\Panels\Accordion( 'content/panels/accordion.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/cardgrid'] = function ( Container $container ) {
			return new Templates\Content\Panels\CardGrid( 'content/panels/cardgrid.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/content-slider'] = function ( Container $container ) {
			return new Templates\Content\Panels\ContentSlider( 'content/panels/content-slider.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/gallery'] = function ( Container $container ) {
			return new Templates\Content\Panels\Gallery( 'content/panels/gallery.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/hero'] = function ( Container $container ) {
			return new Templates\Content\Panels\Hero( 'content/panels/hero.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/interstitial'] = function ( Container $container ) {
			return new Templates\Content\Panels\Interstitial( 'content/panels/interstitial.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/logofarm'] = function ( Container $container ) {
			return new Templates\Content\Panels\LogoFarm( 'content/panels/logofarm.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/micronavbuttons'] = function ( Container $container ) {
			return new Templates\Content\Panels\MicroNavButtons( 'content/panels/micronavbuttons.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/postloop'] = function ( Container $container ) {
			return new Templates\Content\Panels\PostLoop( 'content/panels/postloop.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/tabs'] = function ( Container $container ) {
			return new Templates\Content\Panels\Tabs( 'content/panels/tabs.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/testimonial'] = function ( Container $container ) {
			return new Templates\Content\Panels\Testimonial( 'content/panels/testimonial.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/mediatext'] = function ( Container $container ) {
			return new Templates\Content\Panels\MediaText( 'content/panels/mediatext.twig', $container['twig'] );
		};

		$container['twig.templates.content/panels/wysiwyg'] = function ( Container $container ) {
			return new Templates\Content\Panels\Wysiwyg( 'content/panels/wysiwyg.twig', $container['twig'] );
		};

		$container['twig.templates.sidebar.main'] = function ( Container $container ) {
			return new Templates\Sidebar( 'sidebar.twig', $container['twig'], 'sidebar-main' );
		};
	}

}