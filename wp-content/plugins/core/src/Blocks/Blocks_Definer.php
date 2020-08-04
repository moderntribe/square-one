<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial;
use Tribe\Project\Blocks\Types\Accordion\Accordion;
use Tribe\Project\Blocks\Types\Hero\Hero;
use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form;
use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Components\Handler;

class Blocks_Definer implements Definer_Interface {

	public const TYPES          = 'blocks.types';
	public const CONTROLLER_MAP = 'blocks.controller_map';
	public const BLACKLIST      = 'blocks.blacklist';
	public const STYLES         = 'blocks.style_overrides';

	public function define(): array {
		return [
			self::TYPES => DI\add( [
				DI\get( Accordion::class ),
				DI\get( Hero::class ),
				DI\get( Interstitial::class ),
				DI\get( Lead_Form::class ),
			] ),

			self::CONTROLLER_MAP => DI\add( [] ),

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

			Render_Filter::class  => DI\create()->constructor(
				DI\get( Component_Factory::class ),
				DI\get( Handler::class )
			),
			Allowed_Blocks::class => DI\create()->constructor( DI\get( self::BLACKLIST ) ),

		];
	}
}
