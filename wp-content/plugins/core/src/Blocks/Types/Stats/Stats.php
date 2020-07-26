<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Stats\Support\Statistic;

class Stats extends Block_Type_Config {
	public const NAME = 'tribe/stats';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const STATS       = 'stats';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'layout-inline';
	public const LAYOUT_STACKED = 'layout-stacked';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Stats', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->add_class( 'c-block b-stats l-container' )
			->add_data_source( 'className-c-block', self::LAYOUT )
			->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->links_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-stats__header' )
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'b-stats__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'b-stats__description t-sink s-sink' )
					->build()
			)
			->build();
	}

	private function links_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-stats__content b-stats__list' )
			->add_field(
				$this->factory->content()->field()->flexible_container( self::STATS )
					->set_label( __( 'Stats List', 'tribe' ) )
					->merge_nested_attributes( Statistic::NAME )
					->add_template_block( Statistic::NAME )
					->add_block_type( Statistic::NAME )
					->set_min_blocks( 1 )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
		->add_field(
			$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
				->add_dashicon_option( self::LAYOUT_STACKED, __( 'Stacked', 'tribe' ), 'align-center' )
				->add_dashicon_option( self::LAYOUT_INLINE, __( 'Inline', 'tribe' ), 'align-right' )
				->set_default( self::LAYOUT_STACKED )
				->build()
		)
		->build();
	}

}
