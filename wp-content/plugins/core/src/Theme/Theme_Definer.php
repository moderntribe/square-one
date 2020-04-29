<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Core;
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
	public const RED     = 'red';
	public const ORANGE  = 'orange';
	public const YELLOW  = 'yellow';
	public const GREEN   = 'green';
	public const CYAN    = 'cyan';
	public const MAGENTA = 'purple';

	public const GRADIENT_CYAN_PURPLE = 'cyan-to-purple';
	public const GRADIENT_ORANGE_RED  = 'orange-to-red';

	public function define(): array {
		return [

			/**
			 * Define the colors that will be available in color palettes
			 */
			self::CONFIG_COLORS => [
				self::WHITE   => [ 'color' => '#ffffff', 'label' => __( 'White', 'tribe' ) ],
				self::BLACK   => [ 'color' => '#000000', 'label' => __( 'Black', 'tribe' ) ],
				self::RED     => [ 'color' => '#ff0000', 'label' => __( 'Red', 'tribe' ) ],
				self::ORANGE  => [ 'color' => '#ff8800', 'label' => __( 'Orange', 'tribe' ) ],
				self::YELLOW  => [ 'color' => '#ffff00', 'label' => __( 'Yellow', 'tribe' ) ],
				self::GREEN   => [ 'color' => '#00ff00', 'label' => __( 'Green', 'tribe' ) ],
				self::CYAN    => [ 'color' => '#00ffff', 'label' => __( 'Cyan', 'tribe' ) ],
				self::MAGENTA => [ 'color' => '#ff00ff', 'label' => __( 'Magenta', 'tribe' ) ],
			],

			Colors::class          => DI\create()
				->constructor( DI\get( self::CONFIG_COLORS ) ),

			/**
			 * Define the gradients that will be available in color palettes
			 */
			self::CONFIG_GRADIENTS => [
				self::GRADIENT_CYAN_PURPLE => [
					'gradient' => 'linear-gradient(135deg,rgba(0,255,255,1) 0%,rgb(155,81,224) 100%)',
					'label'    => __( 'Cyan to Purple', 'tribe' ),
				],
				self::GRADIENT_ORANGE_RED  => [
					'gradient' => 'linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%)',
					'label'    => __( 'Orange to Red', 'tribe' ),
				],
			],

			Gradients::class        => DI\create()
				->constructor( DI\get( self::CONFIG_GRADIENTS ) ),

			/**
			 * Define the font sizes that will be available for block settings
			 */
			self::CONFIG_FONT_SIZES => [
				//'large'  => [
				//	'size'  => 36,
				//	'label' => __( 'Large', 'tribe' ),
				//],
			],

			Font_Sizes::class => DI\create()
				->constructor( DI\get( self::CONFIG_FONT_SIZES ) ),

			Oembed_Filter::class => DI\autowire()
				->constructorParameter( 'supported_providers', [
					Oembed_Filter::PROVIDER_VIMEO,
					Oembed_Filter::PROVIDER_YOUTUBE,
				] ),

			/**
			 * The TypeKit kit ID
			 */
			self::CONFIG_TYPEKIT_ID => '',

			/**
			 * Define the Google font families to load
			 */
			self::CONFIG_GOOGLE_FONTS => [],

			/**
			 * Define the custom font families to load
			 */
			self::CONFIG_CUSTOM_FONTS => [],

			Web_Fonts::class => DI\create()
				->constructor( DI\get( Core::PLUGIN_FILE ), [
					Web_Fonts::TYPEKIT_ID => DI\get( self::CONFIG_TYPEKIT_ID ),
					Web_Fonts::GOOGLE     => DI\get( self::CONFIG_GOOGLE_FONTS ),
					Web_Fonts::CUSTOM     => DI\get( self::CONFIG_CUSTOM_FONTS ),
				] ),
		];
	}

}
