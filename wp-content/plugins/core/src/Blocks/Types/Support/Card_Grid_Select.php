<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Card_Grid;

class Card_Grid_Select extends Block_Type_Config {
	public const NAME = Card_Grid::NAME . '--select';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Select Posts', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Card_Grid::NAME )
			->add_content_section( $this->cards_content_section() )
			->build();
	}

	private function cards_content_section(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( Card_Grid::CARDS )
					->set_min_blocks( 2 )
					->set_max_blocks( 4 )
					->add_block_type( Card_Grid_Card::NAME )
					->add_template_block( Card_Grid_Card::NAME )
					->add_template_block( Card_Grid_Card::NAME )
					->discard_nested_content( Card_Grid_Card::NAME )
					->merge_nested_attributes( Card_Grid_Card::NAME )
					->build()
			)
			->build();
	}
}
