<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Types\Accordion\Accordion;
use Tribe\Project\Blocks\Types\Buttons\Buttons;
use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid;
use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Blocks\Types\Gallery_Grid\Gallery_Grid;
use Tribe\Project\Blocks\Types\Gallery_Slider\Gallery_Slider;
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

	public const CONTROLLER_MAP = 'blocks.controller_map';
	public const DENY_LIST      = 'blocks.deny_list';
	public const STYLES         = 'blocks.style_overrides';
	public const TYPES          = 'blocks.types';

	public function define(): array {
		return [
			self::TYPES          => DI\add( [
				DI\get( Accordion::class ),
				DI\get( Buttons::class ),
				DI\get( Card_Grid::class ),
				DI\get( Content_Columns::class ),
				DI\get( Content_Loop::class ),
				DI\get( Gallery_Grid::class ),
				DI\get( Gallery_Slider::class ),
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
			 * Array of blocks blocked by SquareOne. The blocking is handled in
			 * js.
			 *
			 * @see: https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#using-a-deny-list
			 */
			self::DENY_LIST      => [
				'core/archives',
				'core/button',
				'core/buttons',
				'core/calendar',
				'core/categories',
				'core/columns',
				'core/cover',
				'core/gallery',
				'core/html',
				'core/latest-comments',
				'core/latest-posts',
				'core/media-text',
				'core/more',
				'core/nextpage',
				'core/rss',
				'core/search',
				'core/social-links',
				'core/spacer',
				'core/tag-cloud',
				'core/verse',
			],

			/**
			 * An array of block type style overrides
			 *
			 * Each item in the array should be a factory that returns a Block_Style_Override
			 *
			 * TODO: Create a proper thumbnail of the style for the block editor: http://p.tri.be/dmsAwK
			 */
			self::STYLES         => DI\add( [
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

			Denied_Blocks::class => DI\create()->constructor( DI\get( self::DENY_LIST ) ),
		];
	}

}
