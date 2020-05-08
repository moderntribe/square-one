<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Gutenpanels\Blocks\Supports\Align;
use Tribe\Project\Blocks\Block_Type_Config;

class Image_Text extends Block_Type_Config {
	public const NAME = 'tribe/image-text';

	public const IMAGE       = 'image';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';
	public const LAYOUT      = 'layout';
	public const IMAGE_LEFT  = 'image-left';
	public const IMAGE_RIGHT = 'image-right';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Image + Text' )
			->set_dashicon( 'menu-alt3' )
			->supports_align( [ Align::WIDE, Align::FULL ] )
			->add_layout_property( 'grid-template-areas', "'media content'" )
			//->add_conditional_layout_property( 'grid-template-areas', "'content media'", self::LAYOUT, '==', self::IMAGE_RIGHT )
			//->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->media_area() )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function media_area(): Content_Section {
		return $this->factory->content()->section()
			->set_layout_property( 'grid-area', 'media' )
			->add_field(
				$this->factory->content()->field()->image( self::IMAGE )
					->set_label( __( 'Image', 'tribe' ) )
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
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->set_label( __( 'Call to Action', 'tribe' ) )
					->build()
			)
			->build();
	}

}
