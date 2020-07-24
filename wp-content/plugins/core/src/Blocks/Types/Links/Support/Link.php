<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Links\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Links\Links;

class Link extends Block_Type_Config {
	public const NAME = Links::NAME . '--link';

	public const LINK = 'link';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Link', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Links::NAME )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {

		return $this->factory->content()->section()
			->add_class( 'links__list-item' )
			->add_field(
				$this->factory->content()->field()->link( self::LINK )
					->add_class( 'links__list-link' )
					->build()
			)
			->build();
	}

}
