<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Theme\Body_Classes;
use Tribe\Project\Theme\Image_Links;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Image_Wrap;
use Tribe\Project\Theme\Gravity_Forms_Filter;
use Tribe\Project\Theme\Nav\Nav_Attribute_Filters;
use Tribe\Project\Theme\Oembed_Filter;
use Tribe\Project\Theme\Resources\Editor_Styles;
use Tribe\Project\Theme\Resources\Emoji_Disabler;
use Tribe\Project\Theme\Resources\Fonts;
use Tribe\Project\Theme\Resources\Legacy_Check;
use Tribe\Project\Theme\Resources\Login_Resources;
use Tribe\Project\Theme\Resources\Scripts;
use Tribe\Project\Theme\Resources\Styles;
use Tribe\Project\Theme\Supports;
use Tribe\Project\Theme\WP_Responsive_Image_Disabler;

class Theme_Provider implements ServiceProviderInterface {

	private $typekit_id   = '';
	private $google_fonts = [ ];

	/**
	 * Custom (self-hosted) fonts are sourced/imported in the theme: wp-content/themes/core/pcss/base/_fonts.pcss
	 * Declare them here if they require webfont event support (loading, loaded, etc).
	 */
	private $custom_fonts = [ ];

	public function register( Container $container ) {
		$container[ 'theme.body_classes' ] = function ( Container $container ) {
			return new Body_Classes();
		};
		$container[ 'theme.images.sizes' ] = function ( Container $container ) {
			return new Image_Sizes();
		};
		$container[ 'theme.images.wrap' ] = function ( Container $container ) {
			return new Image_Wrap();
		};
		$container[ 'theme.images.links' ] = function ( Container $container ) {
			return new Image_Links();
		};
		$container[ 'theme.images.responsive_disabler' ] = function ( Container $container ) {
			return new WP_Responsive_Image_Disabler();
		};
		$container[ 'theme.oembed' ] = function ( Container $container ) {
			return new Oembed_Filter();
		};
		$container[ 'theme.supports' ] = function ( Container $container ) {
			return new Supports();
		};

		$container[ 'theme.resources.login' ] = function ( Container $container ) {
			return new Login_Resources();
		};
		$container[ 'theme.resources.legacy' ] = function ( Container $container ) {
			return new Legacy_Check();
		};
		$container[ 'theme.resources.emoji_disabler' ] = function ( Container $container ) {
			return new Emoji_Disabler();
		};

		$container[ 'theme.resources.typekit_id' ] = $this->typekit_id;
		$container[ 'theme.resources.google_fonts' ] = $this->google_fonts;
		$container[ 'theme.resources.custom_fonts' ] = $this->custom_fonts;
		$container[ 'theme.resources.fonts' ] = function ( Container $container ) {
			return new Fonts( [
					'typekit' => $container[ 'theme.resources.typekit_id' ],
					'google'  => $container[ 'theme.resources.google_fonts' ],
					'custom'  => $container[ 'theme.resources.custom_fonts' ],
				]
			);
		};

		$container[ 'theme.resources.scripts' ] = function ( Container $container ) {
			return new Scripts();
		};
		$container[ 'theme.resources.styles' ] = function ( Container $container ) {
			return new Styles();
		};
		$container[ 'theme.resources.editor_styles' ] = function ( Container $container ) {
			return new Editor_Styles();
		};

		$container[ 'theme.nav.attribute_filters' ] = function( Container $container ) {
			return new Nav_Attribute_Filters();
		};

		$container[ 'theme.gravity_forms_filter' ] = function ( Container $container ) {
			return new Gravity_Forms_Filter();
		};

		$this->hook( $container );
	}

	private function hook( Container $container ) {
		$container[ 'service_loader' ]->enqueue( 'theme.body_classes', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.sizes', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.wrap', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.links', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.images.responsive_disabler', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.oembed', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.supports', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.login', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.legacy', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.emoji_disabler', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.fonts', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.scripts', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.styles', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.resources.editor_styles', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.nav.attribute_filters', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'theme.gravity_forms_filter', 'hook' );
	}

}