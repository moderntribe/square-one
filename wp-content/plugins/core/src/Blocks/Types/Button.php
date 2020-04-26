<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Button extends Block_Type_Config {

	public const NAME = 'tribe/button';

	public const LINK = 'link';
	public const ARIA = 'aria_label';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Button', 'tribe' ) )
			->set_dashicon( 'align-center' )
			->add_content_section( $this->content_area() )
			->add_style( 'c-btn-primary', __( 'Primary', 'tribe' ), true )
			->add_style( 'c-btn-secondary', __( 'Secondary', 'tribe' ), false )
			->add_style( 'c-btn-tertiary', __( 'Tertiary', 'tribe' ), false )
			->add_style( 'c-text-btn', __( 'Call to Action', 'tribe' ), false )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->link( self::LINK )
					->set_label( __( 'Link', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::ARIA )
					->set_label( __( 'Accessibility Label', 'tribe' ) )
					->set_placeholder( __( 'enter a label for screen readers', 'tribe' ) )
					->build()
			)
			->build();
	}

}
