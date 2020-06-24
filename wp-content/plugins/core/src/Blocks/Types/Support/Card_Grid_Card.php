<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Card_Grid;

class Card_Grid_Card extends Block_Type_Config {
	public const NAME = Card_Grid::NAME . '--card';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Card', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Card_Grid_Select::NAME )
			// TODO: Post Select field
			->add_sidebar_section( $this->post_override_sidebar_section() )
			->build();
	}

	private function post_override_sidebar_section(): Sidebar_Section {
		return $this->factory->sidebar()->section()
			->set_label( __( 'Create or Override', 'tribe' ) )
			->add_field(
				$this->factory->sidebar()->field()->text( Card_Grid::TITLE_OVERRIDE )
					->set_label( __( 'Title', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->sidebar()->field()->richtext( Card_Grid::EXCERPT_OVERRIDE )
					->set_label( __( 'Excerpt', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->sidebar()->field()->image( Card_Grid::IMAGE_OVERRIDE )
					->set_label( __( 'Image', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->sidebar()->field()->link( Card_Grid::LINK_OVERRIDE )
					->set_label( __( 'Link', 'tribe' ) )
					->build()
			)
			->build();
	}
}
