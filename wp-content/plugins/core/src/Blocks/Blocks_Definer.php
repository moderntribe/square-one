<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial;
use Tribe\Project\Blocks\Types\Accordion\Accordion;
use Tribe\Project\Blocks\Types\Hero\Hero;
use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form;
use Tribe\Project\Blocks\Types\Links\Links;
use Tribe\Project\Blocks\Types\Logos\Logos;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text;
use Tribe\Project\Blocks\Types\Quote\Quote;
use Tribe\Project\Blocks\Types\Stats\Stats;
use Tribe\Project\Blocks\Types\Tabs\Tabs;

class Blocks_Definer implements Definer_Interface {

	public const TYPES          = 'blocks.types';
	public const CONTROLLER_MAP = 'blocks.controller_map';
	public const ALLOW_LIST     = 'blocks.allow_list';
	public const STYLES         = 'blocks.style_overrides';

	public function define(): array {
		return [
			self::TYPES => DI\add( [
				DI\get( Accordion::class ),
				DI\get( Hero::class ),
				DI\get( Media_Text::class ),
				DI\get( Interstitial::class ),
				DI\get( Lead_Form::class ),
				DI\get( Links::class ),
				DI\get( Logos::class ),
				DI\get( Quote::class ),
				DI\get( Stats::class ),
				DI\get( Tabs::class ),
			] ),

			/**
			 * Array of blocks supported by SquareOne
			 *
			 * This is an intentional subset of the complete list of block provided by Core:
			 * https://wordpress.org/support/article/blocks/
			 *
			 * Includes our custom ACF blocks
			 * TODO: Find a better method for allowing our custom ACF blocks (above) so we don't have to manually define them here.
			 *
			 * Includes any 3rd-party block supported by this project, such as Gravity Forms.
			 */
			self::ALLOW_LIST => [
				'acf/accordion',
				'acf/hero',
				'acf/mediatext',
				'acf/interstitial',
				'acf/leadform',
				'acf/links',
				'acf/logos',
				'acf/quote',
				'acf/stats',
				'acf/tabs',
				'core/paragraph',
				'core/heading',
				'core/list',
				'core/buttons',
				'core/separator',
				'core/image',
				'core/gallery',
				'core/audio',
				'core/file',
				'core/video',
				'core/code',
				'core/classic',
				'core/custom-html',
				'core/preformatted',
				'core/table',
				'core/shortcode',
				'core/embed',
				'core-embed/facebook',
				'core-embed/twitter',
				'core-embed/youtube',
				'core-embed/instagram',
				'core-embed/wordpress',
				'core-embed/soundcloud',
				'core-embed/flickr',
				'core-embed/vimeo',
				'core-embed/tumblr',
				'core-embed/wordpress-tv',
				'gravityforms/form',
			],

			/**
			 * An array of block type style overrides
			 *
			 * Each item in the array should be a factory that returns a Block_Style_Override
			 *
			 * TODO: Create a proper thumbnail of the style for the block editor: http://p.tri.be/dmsAwK
			 */
			self::STYLES     => DI\add( [
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

			Allowed_Blocks::class => DI\create()->constructor( DI\get( self::ALLOW_LIST ) ),
		];
	}
}
