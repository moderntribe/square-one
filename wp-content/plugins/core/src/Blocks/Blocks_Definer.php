<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Global_Fields\Block_Controller;
use Tribe\Project\Blocks\Global_Fields\Color_Theme\Color_Theme_Meta;
use Tribe\Project\Blocks\Global_Fields\Color_Theme\Color_Theme_Model;
use Tribe\Project\Blocks\Types\Accordion\Accordion;
use Tribe\Project\Blocks\Types\Buttons\Buttons;
use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid;
use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Blocks\Types\Gallery_Grid\Gallery_Grid;
use Tribe\Project\Blocks\Types\Hero\Hero;
use Tribe\Project\Blocks\Types\Icon_Grid\Icon_Grid;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial;
use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form;
use Tribe\Project\Blocks\Types\Links\Links;
use Tribe\Project\Blocks\Types\Logos\Logos;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text;
use Tribe\Project\Blocks\Types\Quote\Quote;
use Tribe\Project\Blocks\Types\Spacer\Spacer;
use Tribe\Project\Blocks\Types\Stats\Stats;
use Tribe\Project\Blocks\Types\Tabs\Tabs;

class Blocks_Definer implements Definer_Interface {

	public const TYPES                         = 'blocks.types';
	public const CONTROLLER_MAP                = 'blocks.controller_map';
	public const ALLOW_LIST                    = 'blocks.allow_list';
	public const STYLES                        = 'blocks.style_overrides';
	public const GLOBAL_BLOCK_FIELD_COLLECTION = 'blocks.global_block_field_collection';
	public const GLOBAL_MODEL_COLLECTION       = 'blocks.global_model_collection';
	public const ALLOWED_GLOBAL_BLOCKS         = 'blocks.allowed_global_blocks';

	public function define(): array {
		return [
			self::TYPES      => DI\add( [
				DI\get( Accordion::class ),
				DI\get( Buttons::class ),
				DI\get( Card_Grid::class ),
				DI\get( Content_Columns::class ),
				DI\get( Content_Loop::class ),
				DI\get( Gallery_Grid::class ),
				DI\get( Hero::class ),
				DI\get( Icon_Grid::class ),
				DI\get( Interstitial::class ),
				DI\get( Lead_Form::class ),
				DI\get( Links::class ),
				DI\get( Logos::class ),
				DI\get( Media_Text::class ),
				DI\get( Quote::class ),
				DI\get( Spacer::class ),
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
			 * TODO: Add a filter for this list
			 *
			 * Includes any 3rd-party block supported by this project, such as Gravity Forms.
			 */
			self::ALLOW_LIST => [
				'acf/accordion',
				'acf/buttons',
				'acf/cardgrid',
				'acf/contentcolumns',
				'acf/contentloop',
				'acf/gallerygrid',
				'acf/hero',
				'acf/icongrid',
				'acf/interstitial',
				'acf/leadform',
				'acf/links',
				'acf/logos',
				'acf/mediatext',
				'acf/quote',
				'acf/spacer',
				'acf/stats',
				'acf/tabs',
				'core-embed/facebook',
				'core-embed/flickr',
				'core-embed/instagram',
				'core-embed/soundcloud',
				'core-embed/tumblr',
				'core-embed/twitter',
				'core-embed/vimeo',
				'core-embed/wordpress-tv',
				'core-embed/wordpress',
				'core-embed/youtube',
				'core/audio',
				//'core/buttons',
				'core/block',
				'core/code',
				'core/embed',
				'core/file',
				'core/freeform',
				'core/gallery',
				'core/heading',
				'core/html',
				'core/image',
				'core/list',
				'core/paragraph',
				'core/preformatted',
				'core/quote',
				'core/separator',
				'core/shortcode',
				'core/table',
				'core/video',
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

			Allowed_Blocks::class               => DI\create()->constructor( DI\get( self::ALLOW_LIST ) ),

			/**
			 * Define global block field instances. Their fields will
			 * be appended to existing blocks in the list below.
			 */
			self::GLOBAL_BLOCK_FIELD_COLLECTION => DI\add( [
				DI\get( Color_Theme_Meta::class ),
			] ),

			/**
			 * Define global field model instances to match the field
			 * instances above. This field data will be passed to existing
			 * block controllers in the list below.
			 */
			self::GLOBAL_MODEL_COLLECTION       => DI\add( [
				DI\get( Color_Theme_Model::class ),
			] ),

			/**
			 * Define the block names that will automatically include Global block
			 * fields.
			 *
			 * Optionally: The block name can be a key, with Meta/Model classes as values
			 * to specify the exact Global block fields that block will use, and ignore
			 * all others. Ensure to include BOTH the Meta/Model classes.
			 */
			self::ALLOWED_GLOBAL_BLOCKS         => DI\add( [
				Accordion::NAME,
				Buttons::NAME,
				Card_Grid::NAME,
				Content_Columns::NAME,
				Content_Loop::NAME,
				Gallery_Grid::NAME,
				Hero::NAME => [
					Color_Theme_Meta::class,
					Color_Theme_Model::class,
				],
				Interstitial::NAME,
				Lead_Form::NAME,
				Links::NAME,
				Logos::NAME,
				Media_Text::NAME,
				Quote::NAME,
				Spacer::NAME,
				Stats::NAME,
				Tabs::NAME,
			] ),

			Block_Controller::class             => DI\autowire()
				->constructor( DI\get( self::ALLOWED_GLOBAL_BLOCKS ) ),
		];
	}
}
