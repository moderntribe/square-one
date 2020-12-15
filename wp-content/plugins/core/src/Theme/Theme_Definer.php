<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Theme\Config\Colors;
use Tribe\Project\Theme\Config\Font_Sizes;
use Tribe\Project\Theme\Config\Gradients;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Media\Oembed_Filter;

class Theme_Definer implements Definer_Interface {
	public const CONFIG_COLORS       = 'theme.config.colors';
	public const CONFIG_GRADIENTS    = 'theme.config.gradients';
	public const CONFIG_FONT_SIZES   = 'theme.config.font-sizes';
	public const CONFIG_TYPEKIT_ID   = 'theme.config.typekit_id';
	public const CONFIG_GOOGLE_FONTS = 'theme.config.google_fonts';
	public const CONFIG_CUSTOM_FONTS = 'theme.config.custom_fonts';

	public const BLACK   = 'black';
	public const WHITE   = 'white';
	public const GREY    = 'grey';
	public const BLUE    = 'blue';
	public const GREEN   = 'green';

	public const GRADIENT_CYAN_PURPLE = 'cyan-to-purple';
	public const GRADIENT_ORANGE_RED  = 'orange-to-red';

	public function define(): array {
		return [

			/**
			 * Define the colors that will be available in color palettes
			 */
			self::CONFIG_COLORS => [
				self::WHITE   => [ 'color' => '#ffffff', 'label' => __( 'White', 'tribe' ) ],
				self::BLACK   => [ 'color' => '#151515', 'label' => __( 'Black', 'tribe' ) ],
				self::GREY    => [ 'color' => '#696969', 'label' => __( 'Grey', 'tribe' ) ],
				self::BLUE    => [ 'color' => '#0074e0', 'label' => __( 'Blue', 'tribe' ) ],
				self::GREEN   => [ 'color' => '#18814e', 'label' => __( 'Green', 'tribe' ) ],
			],

			Colors::class          => DI\create()
				->constructor( DI\get( self::CONFIG_COLORS ) ),

			/**
			 * Define the gradients that will be available in color palettes
			 *
			 * Note: Gradients are disabled by default.
			 */
			self::CONFIG_GRADIENTS => [
				/*self::GRADIENT_CYAN_PURPLE => [
					'gradient' => 'linear-gradient(135deg,rgba(0,255,255,1) 0%,rgb(155,81,224) 100%)',
					'label'    => __( 'Cyan to Purple', 'tribe' ),
				],
				self::GRADIENT_ORANGE_RED  => [
					'gradient' => 'linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%)',
					'label'    => __( 'Orange to Red', 'tribe' ),
				],*/
			],

			Gradients::class        => DI\create()
				->constructor( DI\get( self::CONFIG_GRADIENTS ) ),

			/**
			 * Define the font sizes that will be available for block settings
			 *
			 * Note: Custom font sizes are disabled by default.
			 */
			self::CONFIG_FONT_SIZES => [
				//'large'  => [
				//	'size'  => 36,
				//	'label' => __( 'Large', 'tribe' ),
				//],
			],

			Font_Sizes::class => DI\create()
				->constructor( DI\get( self::CONFIG_FONT_SIZES ) ),

			/**
			 * Enable our custom oEmbed cover image markup.
			 *
			 * TODO: FIx this. Currently disabled b/c Gutenberg is busted w/ our custom oEmbed covers.
			 */
			Oembed_Filter::class => DI\autowire()
				->constructorParameter( 'supported_providers', [
					//Oembed_Filter::PROVIDER_VIMEO,
					//Oembed_Filter::PROVIDER_YOUTUBE,
				] ),

			/**
			 * @var string A valid TypeKit (Adobe Fonts) Project ID.
			 *
			 * Example: `zbo7iia`
			 */
			self::CONFIG_TYPEKIT_ID => '',

			/**
			 * @var array A collection of Google Font families to load.
			 *
			 * A complete URL for Google Fonts usage.
			 *
			 * Example: `https://fonts.googleapis.com/css2?family=Crimson+Pro&family=Literata`
			 *
			 * @link https://developers.google.com/fonts/docs/css2
			 */
			self::CONFIG_GOOGLE_FONTS => '',

			/**
			 * @var array Collection of custom webfont sources.
			 *
			 * An associative array of css files containing custom @font-face definitions.
			 * Useful for other 3rd-party web font providers such as fonts.com.
			 *
			 * Example: `[ 'fonts_com' => 'https://fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css' ]`
			 *
			 * Key name is a unique name (slug) for the enqueued stylesheet.
			 */
			self::CONFIG_CUSTOM_FONTS => [],

			Web_Fonts::class => DI\create()
				->constructor( [
					Web_Fonts::PROVIDER_TYPEKIT => DI\get( self::CONFIG_TYPEKIT_ID ),
					Web_Fonts::PROVIDER_GOOGLE  => DI\get( self::CONFIG_GOOGLE_FONTS ),
					Web_Fonts::PROVIDER_CUSTOM  => DI\get( self::CONFIG_CUSTOM_FONTS ),
				] ),
		];
	}

}
