<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Gutenpanels\Blocks\Supports\Align;
use Tribe\Project\Blocks\Block_Type_Config;

class Interstitial extends Block_Type_Config {
	public const NAME = 'tribe/interstitial';

	public const IMAGE         = 'image';
	public const DESCRIPTION   = 'description';
	public const CTA           = 'cta';
	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'left';
	public const LAYOUT_CENTER = 'center';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Interstitial' )
			->set_dashicon( 'menu-alt' )
			->add_data_source( 'background-image', self::IMAGE )
			->add_sidebar_section( $this->background() )
			->add_content_section( $this->content_area() )
			->add_toolbar_section( $this->layout_toolbar() )
			->build();
	}

	private function background(): Sidebar_Section {
		return $this->factory->sidebar()->section()
			->add_field(
				$this->factory->content()->field()->image( self::IMAGE )
					->set_label( __( 'Background Image', 'tribe' ) )
					->build()
			)
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
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

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::LAYOUT_LEFT, __( 'Align Left', 'tribe' ), 'editor-alignleft' )
					->add_dashicon_option( self::LAYOUT_CENTER, __( 'Align Center', 'tribe' ), 'editor-aligncenter' )
					->build()
			)
			->build();
	}

}
