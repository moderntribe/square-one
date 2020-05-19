<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Card_Grid_Query;
use Tribe\Project\Blocks\Types\Support\Card_Grid_Select;

class Card_Grid extends Block_Type_Config {

	public const NAME = 'tribe/card-grid';

	public const LAYOUT      = 'layout';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';
	public const CARDS       = 'cards';

	public const TITLE_OVERRIDE   = 'title-override';
	public const EXCERPT_OVERRIDE = 'excerpt-override';
	public const IMAGE_OVERRIDE   = 'image-override';
	public const LINK_OVERRIDE    = 'link-override';

	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Card Grid' )
			->set_dashicon( 'menu-alt' )
			->add_sidebar_section( $this->layout_sidebar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->cards_area() )
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
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->build()
			)
			->build();
	}

	private function cards_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( self::CARDS )
					->set_label( 'Cards' )
					->add_block_type( Card_Grid_Query::NAME )
					->add_block_type( Card_Grid_Select::NAME )
					->merge_nested_attributes( Card_Grid_Query::NAME )
					->merge_nested_attributes( Card_Grid_Select::NAME )
					->add_template_block( Card_Grid_Select::NAME )
					->set_min_blocks( 1 )
					->set_max_blocks( 1 )
					->build()
			)
			->build();
	}


}
