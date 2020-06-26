<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Accordion_Section;

class Accordion extends Block_Type_Config {
	public const NAME = 'tribe/accordion';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const ACCORDION   = 'accordion';

	public const LAYOUT         = 'layout';
	public const LAYOUT_LEFT    = 'layout-left';
	public const LAYOUT_RIGHT   = 'layout-right';
	public const LAYOUT_STACKED = 'layout-stacked';
	public const WIDTH          = 'width';
	public const WIDTH_CENTER   = 'width-center';
	public const WIDTH_GRID     = 'width-grid';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Accordion', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->add_data_source( 'className-c-panel', self::LAYOUT )
			->add_data_source( 'className-c-panel', self::WIDTH )
			->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->accordions_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'accordion__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'accordion__description t-sink s-sink' )
					->build()
			)
			->build();
	}

	private function accordions_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( self::ACCORDION )
					->set_label( __( 'Accordion', 'tribe' ) )
					->merge_nested_attributes( Accordion_Section::NAME )
					->add_template_block( Accordion_Section::NAME )
					->add_block_type( Accordion_Section::NAME )
					->set_min_blocks( 1 )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::LAYOUT_LEFT, __( 'Accordion Left', 'tribe' ), 'editor-alignleft' )
					->add_dashicon_option( self::LAYOUT_STACKED, __( 'Accordion Stacked', 'tribe' ), 'editor-aligncenter' )
					->add_dashicon_option( self::LAYOUT_RIGHT, __( 'Accordion Right', 'tribe' ), 'editor-alignright' )
					->set_default( self::LAYOUT_STACKED )
					->build()
			)
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::WIDTH )
					->add_dashicon_option( self::WIDTH_CENTER, __( 'Content', 'tribe' ), 'editor-contract' )
					->add_dashicon_option( self::WIDTH_GRID, __( 'Full', 'tribe' ), 'editor-expand' )
					->build()
			)
			->build();
	}

}
