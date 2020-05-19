<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Card_Grid;
use Tribe\Project\Blocks\Types\Content_Carousel;

class Content_Carousel_Select extends Block_Type_Config {
	public const NAME = Content_Carousel::NAME . '--select';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Select Posts', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Content_Carousel::NAME )
			->add_content_section( $this->cards_content_section() )
			->build();
	}

	private function cards_content_section(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( Content_Carousel::CARDS )
					->set_min_blocks( 4 )
					->set_max_blocks( 12 )
					->add_block_type( Content_Carousel_Card::NAME )
					->add_template_block( Content_Carousel_Card::NAME )
					->add_template_block( Content_Carousel_Card::NAME )
					->discard_nested_content( Content_Carousel_Card::NAME )
					->merge_nested_attributes( Content_Carousel_Card::NAME )
					->build()
			)
			->build();
	}
}
