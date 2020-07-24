<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Interstitial;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Interstitial extends Block_Type_Config {
	public const NAME = 'tribe/interstitial';

	public const IMAGE         = 'image';
	public const DESCRIPTION   = 'description';
	public const CTA           = 'cta';
	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'layout-left';
	public const LAYOUT_CENTER = 'layout-center';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Interstitial', 'tribe' ) )
			->add_class( 'c-block c-panel--full-bleed b-interstitial' )
			->add_data_source( 'className-c-block', self::LAYOUT )
			->set_dashicon( 'menu-alt' )
			->add_data_source( 'background-image', self::IMAGE ) /* TEMP until we get support for this on the HTML field. */
			->add_sidebar_section( $this->background() )
			// ->add_content_section( $this->background_area() )
			->add_content_section( $this->content_area() )
			->add_toolbar_section( $this->layout_toolbar() )
			->build();
	}

	private function background(): Sidebar_Section {
		return $this->factory->sidebar()->section()
			->set_label( __( 'Background Settings', 'tribe' ) )
			->add_field(
				$this->factory->content()->field()->image( self::IMAGE )
					->set_label( __( 'Background Image', 'tribe' ) )
					->build()
			)
			->build();
	}

	/* TODO: Enable this once the html field type get `set_data_source()` support
	private function background_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-interstitial__figure' )
			->add_field(
				$this->factory->content()->field()->html( 'bkgrd' )
					->add_class( 'b-interstitial__img c-image__bg' )
					->set_content( '<div></div>' )
					->add_data_source( 'background-image', self::IMAGE )
					->build()
			)
			->build();
	}*/

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-interstitial__content b-interstitial__content-container t-theme--light' )
			->add_field(
				$this->factory->content()->field()->text( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->set_placeholder( 'Headline' )
					->add_class( 'b-interstitial__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->set_label( __( 'Call to Action', 'tribe' ) )
					->add_class( 'a-btn a-btn--has-icon-after icon-arrow-right' )
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
