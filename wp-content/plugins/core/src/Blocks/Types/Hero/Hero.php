<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Hero extends Block_Type_Config {

	public const NAME = 'tribe/hero';

	public const IMAGE         = 'image';
	public const LEAD_IN       = 'leadin';
	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const CTA           = 'cta';
	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'layout-left';
	public const LAYOUT_CENTER = 'layout-center';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Hero', 'tribe' ) )
			->add_class( 'c-panel c-panel--hero c-panel--full-bleed' )
			->add_data_source( 'className-c-panel', self::LAYOUT )
			->set_dashicon( 'menu-alt' )
			->add_sidebar_section( $this->background_sidebar() )
			->add_data_source( 'background-image', self::IMAGE ) /* TEMP until we get support for this on the HTML field. */
			// ->add_content_section( $this->background_area() )
			->add_content_section( $this->content_area() )
			->add_toolbar_section( $this->layout_toolbar() )
			->build();
	}

	private function background_sidebar(): Sidebar_Section {
		return $this->factory->sidebar()->section()
			->set_label( __( 'Background Settings', 'tribe' ) )
			->add_field(
				$this->factory->sidebar()->field()->image( self::IMAGE )
					->set_label( __( 'Background Image', 'tribe' ) )
					->build()
			)
			->build();
	}

	/* TODO: Enable this once the html field type get `set_data_source()` support
	private function background_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'hero__figure' )
			->add_field(
				$this->factory->content()->field()->html( 'bkgrd' )
					->add_class( 'hero__img c-image__bg' )
					->set_content( '<div></div>' )
					->add_data_source( 'background-image', self::IMAGE )
					->build()
			)
			->build();
	}*/

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'hero__content hero__content-container t-theme--light' )
			->add_field(
				$this->factory->content()->field()->text( self::LEAD_IN )
					->set_label( __( 'Lead-In', 'tribe' ) )
					->set_placeholder( 'Lead-In or overline' )
					->add_class( 'hero__leadin h6' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Headline', 'tribe' ) )
					->set_placeholder( 'Headline' )
					->add_class( 'hero__title h1' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'hero__description t-sink s-sink' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->set_label( __( 'Call to Action', 'tribe' ) )
					->add_class( 'test-hero__cta a-btn' )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::LAYOUT_LEFT, __( 'Align Text Left', 'tribe' ), 'editor-alignleft' )
					->add_dashicon_option( self::LAYOUT_CENTER, __( 'Align Text Center', 'tribe' ), 'editor-aligncenter' )
					->set_default( self::LAYOUT_LEFT )
					->build()
			)
			->build();
	}

}
