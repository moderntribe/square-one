<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Supports\Align;
use Tribe\Project\Blocks\Block_Type_Config;

class Icon_Grid extends Block_Type_Config {
	public const NAME = 'tribe/icon-grid';

	public const HEADER = 'header';
	public const SUBHEADER = 'subheader';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Icon Grid' )
			->set_dashicon( 'menu-alt3' )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->text( self::HEADER )
					->set_label( __( 'Header', 'tribe' ) )
					->set_placeholder( __( 'Enter header...', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::SUBHEADER )
					->set_label( __( 'Subheader', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->set_label( __( 'Call to Action', 'tribe' ) )
					->build()
			)
			->build();
	}

}
