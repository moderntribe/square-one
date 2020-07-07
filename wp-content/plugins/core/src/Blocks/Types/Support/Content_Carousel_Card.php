<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Content_Carousel;

class Content_Carousel_Card extends Block_Type_Config {
	public const NAME = Content_Carousel::NAME . '--card';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Card', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Content_Carousel_Select::NAME )
			// TODO: Post Select field
			->add_sidebar_section( $this->post_override_sidebar_section() )
			->build();
	}

	private function post_override_sidebar_section(): Sidebar_Section {
		return $this->factory->sidebar()->section()
			->set_label( __( 'Create or Override', 'tribe' ) )
			->add_field(
				$this->factory->sidebar()->field()->text( Content_Carousel::TITLE_OVERRIDE )
					->set_label( __( 'Title', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->sidebar()->field()->image( Content_Carousel::IMAGE_OVERRIDE )
					->set_label( __( 'Image', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->sidebar()->field()->link( Content_Carousel::LINK_OVERRIDE )
					->set_label( __( 'Link', 'tribe' ) )
					->build()
			)
			->build();
	}
}
