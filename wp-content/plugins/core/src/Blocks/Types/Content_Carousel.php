<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Card_Grid_Query;
use Tribe\Project\Blocks\Types\Support\Card_Grid_Select;
use Tribe\Project\Blocks\Types\Support\Content_Carousel_Query;
use Tribe\Project\Blocks\Types\Support\Content_Carousel_Select;

class Content_Carousel extends Block_Type_Config {

	public const NAME = 'tribe/content-carousel';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CARDS       = 'cards';

	public const TITLE_OVERRIDE   = 'title-override';
	public const IMAGE_OVERRIDE   = 'image-override';
	public const LINK_OVERRIDE    = 'link-override';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Content Carousel' )
			->set_dashicon( 'menu-alt' )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->cards_area() )
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
			->build();
	}

	private function cards_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( self::CARDS )
					->set_label( 'Cards' )
					->add_block_type( Content_Carousel_Query::NAME )
					->add_block_type( Content_Carousel_Select::NAME )
					->merge_nested_attributes( Content_Carousel_Query::NAME )
					->set_nested_namespace( Content_Carousel_Query::NAME, 'query' )
					->set_nested_namespace( Content_Carousel_Select::NAME, 'select' )
					->merge_nested_attributes( Content_Carousel_Select::NAME )
					->add_template_block( Content_Carousel_Select::NAME )
					->set_min_blocks( 1 )
					->set_max_blocks( 1 )
					->build()
			)
			->build();
	}


}
