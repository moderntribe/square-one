<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Quote extends Block_Type_Config {

	public const NAME = 'tribe/quote';

	public const IMAGE         = 'image';
	public const QUOTE         = 'quote';
	public const CITE_NAME     = 'cite_name';
	public const CITE_TITLE    = 'cite_title';
	public const CITE_IMAGE    = 'cite_image';
	public const LAYOUT        = 'layout';
	public const MEDIA_LEFT    = 'layout-left';
	public const MEDIA_RIGHT   = 'layout-right';
	public const MEDIA_OVERLAY = 'layout-overlay';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Quote', 'tribe' ) )
			->add_class( 'c-block c-panel--full-bleed b-quote' )
			->add_data_source( 'className-c-block', self::LAYOUT )
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
			->add_class( 'b-quote__figure' )
			->add_field(
				$this->factory->content()->field()->html( 'bkgrd' )
					->add_class( 'b-quote__img c-image__bg' )
					->set_content( '<div></div>' )
					->add_data_source( 'background-image', self::IMAGE )
					->build()
			)
			->build();
	}*/

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-quote__content b-quote__content-container t-theme--light' )
			->add_field(
				$this->factory->content()->field()->text( self::QUOTE )
					->set_label( __( 'Quotation', 'tribe' ) )
					->add_class( 'b-quote__text' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::CITE_NAME )
					->set_label( __( 'Citation Name', 'tribe' ) )
					->set_placeholder( 'Citation Name' )
					->add_class( 'b-quote__cite-name' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::CITE_TITLE )
					->set_label( __( 'Citation Title', 'tribe' ) )
					->set_placeholder( 'Citation Title' )
					->add_class( 'b-quote__cite-title' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->image( self::CITE_IMAGE )
					->set_label( __( 'Citation Image', 'tribe' ) )
					->add_class( 'b-quote__cite-image' )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::MEDIA_LEFT, __( 'Image Left', 'tribe' ), 'editor-alignleft' )
					->add_dashicon_option( self::MEDIA_OVERLAY, __( 'Image Overlay', 'tribe' ), 'editor-aligncenter' )
					->add_dashicon_option( self::MEDIA_RIGHT, __( 'Image Right', 'tribe' ), 'editor-alignright' )
					->set_default( self::MEDIA_OVERLAY )
					->build()
			)
			->build();
	}

}
