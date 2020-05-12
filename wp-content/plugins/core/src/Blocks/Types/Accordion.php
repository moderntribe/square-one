<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Accordion_Section;

class Accordion extends Block_Type_Config {

	public const NAME = 'tribe/accordion';

	public const LAYOUT      = 'layout';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const ACCORDION   = 'accordion';

	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Accordion' )
			->set_dashicon( 'menu-alt' )
			->add_layout_property( 'grid-template-areas', "'content' 'accordion'" )
			->add_sidebar_section( $this->layout_sidebar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->accordions_area() )
			->build();
	}

	private function layout_sidebar(): Sidebar_Section {
		return $this->factory->sidebar()->section()->set_label( __( 'Layout Settings', 'tribe' ) )
			->add_field(
				$this->factory->sidebar()->field()->image_select( self::LAYOUT )
					->set_label( __( 'Layout', 'tribe' ) )
					->add_option( self::LAYOUT_STACKED, __( 'Stacked', 'tribe' ), 'https://via.placeholder.com/100x60.png?text=Stacked' )
					->add_option( self::LAYOUT_INLINE, __( 'Inline', 'tribe' ), 'https://via.placeholder.com/100x60.png?text=Inline' )
					->set_default( self::LAYOUT_STACKED )
					->build()
			)
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->set_layout_property( 'grid-area', 'content' )
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'h2' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->build()
			)
			->build();
	}

	private function accordions_area(): Content_Section {
		return $this->factory->content()->section()
			->set_layout_property( 'grid-area', 'accordion' )
			->add_field(
				$this->factory->content()->field()->flexible_container( self::ACCORDION )
					->set_label( 'Accordion' )
					->merge_nested_attributes( Accordion_Section::NAME )
					->add_template_block( Accordion_Section::NAME )
					->add_block_type( Accordion_Section::NAME )
					->set_min_blocks( 1 )
					->build()
			)
			->build();
	}


}
