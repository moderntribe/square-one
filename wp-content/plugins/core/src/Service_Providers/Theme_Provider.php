<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Theme\Body_Classes;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Image_Wrap;
use Tribe\Project\Theme\Oembed_Wrap;
use Tribe\Project\Theme\WP_Responsive_Image_Disabler;

class Theme_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container[ 'theme.body_classes' ] = function( Container $container ) {
			return new Body_Classes();
		};
		$container[ 'theme.images.sizes' ] = function( Container $container ) {
			return new Image_Sizes();
		};
		$container[ 'theme.images.wrap' ] = function( Container $container ) {
			return new Image_Wrap();
		};
		$container[ 'theme.images.responsive_disabler' ] = function( Container $container ) {
			return new WP_Responsive_Image_Disabler();
		};
		$container[ 'theme.oembed.wrap' ] = function( Container $container ) {
			return new Oembed_Wrap();
		};

		$this->hook( $container );
	}

	private function hook( Container $container ) {
		$container[ 'service_loader' ]->enqueue( 'theme.body_classes', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.sizes', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.wrap', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.responsive_disabler', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.oembed.wrap', 'hook' );
	}

}