<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Builder\Block_Builder;
use Tribe\Project\Blocks\Builder\Builder_Factory;
use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Components\Handler;
use Tribe\Project\Controllers\Blocks\Hero;
use Tribe\Project\Controllers\Blocks\Media_Text;
use Tribe\Project\Templates\Controllers;

class Blocks_Definer implements Definer_Interface {

	public const TYPES          = 'blocks.types';
	public const CONTROLLER_MAP = 'blocks.controller_map';
	public const BLACKLIST      = 'blocks.blacklist';
	public const STYLES         = 'blocks.style_overrides';

	public function define(): array {
		return [
			self::TYPES => DI\add( [
				DI\get( Types\Accordion::class ),
				DI\get( Types\Support\Accordion_Section::class ),

				DI\get( Types\Button::class ),

				DI\get( Types\Card_Grid::class ),
				DI\get( Types\Support\Card_Grid_Query::class ),
				DI\get( Types\Support\Card_Grid_Select::class ),
				DI\get( Types\Support\Card_Grid_Card::class ),

				DI\get( Types\Content_Carousel::class ),
				DI\get( Types\Support\Content_Carousel_Query::class ),
				DI\get( Types\Support\Content_Carousel_Select::class ),
				DI\get( Types\Support\Content_Carousel_Card::class ),

				DI\get( Types\Hero::class ),

				DI\get( Types\Icon_Grid::class ),
				DI\get( Types\Support\Icon_Grid_Card::class ),

				DI\get( Types\Interstitial::class ),

				DI\get( Types\Media_Text::class ),
				DI\get( Types\Support\Media_Text_Media::class ),
				DI\get( Types\Support\Media_Text_Media_Embed::class ),
				DI\get( Types\Support\Media_Text_Media_Image::class ),
				DI\get( Types\Support\Media_Text_Text::class ),
			] ),

			self::CONTROLLER_MAP => DI\add( [
				Types\Media_Text::NAME => Media_Text::class,
				Types\Hero::NAME => Hero::class,
			] ),

			/**
			 * An array of core/3rd-party block types that should be unregistered
			 */ self::BLACKLIST  => [
				'core/buttons',
				'core/button',
				'core/rss',
				'core/social-links',
				'core/spacer',
			],

			/**
			 * An array of block type style overrides
			 *
			 * Each item in the array should be a factory that returns a Block_Style_Override
			 */ self::STYLES     => DI\add( [
				DI\factory( static function () {
					return new Block_Style_Override( [ 'core/paragraph' ], [
						[
							'name'  => 't-overline',
							'label' => __( 'Overline', 'tribe' ),
						],
						[
							'name'  => 't-leadin',
							'label' => __( 'Lead-In', 'tribe' ),
						],
					] );
				} ),
				DI\factory( static function () {
					return new Block_Style_Override( [ 'core/list' ], [
						[
							'name'  => 't-list-stylized',
							'label' => __( 'Stylized', 'tribe' ),
						],
					] );
				} ),
			] ),

			Render_Filter::class => DI\create()->constructor( DI\get( Component_Factory::class ), DI\get( Handler::class ), DI\get( self::CONTROLLER_MAP ) ),

			Allowed_Blocks::class => DI\create()->constructor( DI\get( self::BLACKLIST ) ),

			\Tribe\Gutenpanels\Builder\Block_Builder::class             => DI\get( Block_Builder::class ),
			\Tribe\Gutenpanels\Builder\Factories\Builder_Factory::class => DI\get( Builder_Factory::class ),
		];
	}
}
