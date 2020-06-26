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
	public const LAYOUT_INLINE  = 'layout-inline';
	public const LAYOUT_STACKED = 'layout-stacked';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Accordion', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->add_class( 'c-panel c-panel--accordion l-container' )
			->add_data_source( 'className-c-panel', self::LAYOUT )
			->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->accordions_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'accordion__header' )
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
			->add_class( 'accordion__content' )
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
					->add_dashicon_option( self::LAYOUT_STACKED, __( 'Stacked', 'tribe' ), 'align-center' )
					->add_dashicon_option( self::LAYOUT_INLINE, __( 'Inline', 'tribe' ), 'align-right' )
					->set_default( self::LAYOUT_STACKED )
					->build()
			)
			->build();
	}

}
