<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Lead_Form extends Block_Type_Config {
	public const NAME = 'tribe/lead-form';

	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const FORM          = 'form';
	public const LAYOUT        = 'layout';
	public const LAYOUT_CENTER = 'layout-center';
	public const LAYOUT_LEFT   = 'layout-left';
	public const WIDTH         = 'width';
	public const WIDTH_GRID    = 'width-grid';
	public const WIDTH_FULL    = 'width-full';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Lead Form', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->add_class( 'c-block b-lead-form' )
			->add_data_source( 'className-c-block', self::LAYOUT )
			->add_data_source( 'className-c-block', self::WIDTH )
			->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'b-lead-form__content' )
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'b-lead-form__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'b-lead-form__description t-sink s-sink' )
					->build()
			)
			// TODO: Add Gravity Forms form select field here?
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::LAYOUT_CENTER, __( 'Content Center', 'tribe' ), 'editor-aligncenter' )
					->add_dashicon_option( self::LAYOUT_LEFT, __( 'Content Left', 'tribe' ), 'editor-alignleft' )
					->set_default( self::LAYOUT_CENTER )
					->build()
			)
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::WIDTH )
					->add_dashicon_option( self::WIDTH_GRID, __( 'Grid', 'tribe' ), 'editor-contract' )
					->add_dashicon_option( self::WIDTH_FULL, __( 'Full', 'tribe' ), 'editor-expand' )
					->set_default( self::WIDTH_GRID )
					->build()
			)
			->build();
	}
}
