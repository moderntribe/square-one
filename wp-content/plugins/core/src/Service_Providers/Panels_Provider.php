<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Panels;

class Panels_Provider implements ServiceProviderInterface {

	protected $panels = [
		Panels\Types\Hero::class,
		Panels\Types\Accordion::class,
		Panels\Types\CardGrid::class,
		Panels\Types\Gallery::class,
		Panels\Types\ImageText::class,
		Panels\Types\VideoText::class,
		Panels\Types\Interstitial::class,
		Panels\Types\MicroNavButtons::class,
		Panels\Types\Wysiwyg::class,
		Panels\Types\ContentSlider::class,
		Panels\Types\LogoFarm::class,
		Panels\Types\Testimonial::class,
		Panels\Types\PostLoop::class,
	];

	public function register( Container $container ) {
		$container[ 'panels.plugin' ] = function( Container $container ) {
			return \ModularContent\Plugin::instance();
		};

		$container[ 'panels.init' ] = function ( $container ) {
			$init = new Panels\Initializer( $container[ 'plugin_file' ] );

			return $init;
		};

		add_action( 'plugins_loaded', function () use ( $container ) {
			$container[ 'panels.init' ]->set_labels();

			foreach ( $this->panels as $panel ) {
				$container[ 'panels.init' ]->add_panel_config( $panel );
			}
		}, 9, 0 );

		add_action( 'panels_init', function() use ( $container ) {
			$container[ 'panels.init' ]->initialize_panels( $container[ 'panels.plugin' ] );
		}, 10, 0 );

		add_filter( 'panels_js_config', function( $data ) use ( $container ) {
			return $container[ 'panels.init' ]->modify_js_config( $data );
		}, 10, 1 );
	}
}