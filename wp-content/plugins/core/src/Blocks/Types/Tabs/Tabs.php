<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Tabs\Support\Tabs_Section;

class Tabs extends Block_Type_Config {
	public const NAME = 'tribe/tabs';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const TABS   = 'tabs';

	public const LAYOUT            = 'layout';
	public const LAYOUT_HORIZONTAL = 'layout-horizontal';
	public const LAYOUT_VERTICAL   = 'layout-vertical';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Tabs', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->add_class( 'c-panel c-panel--tabs l-container' )
			->add_data_source( 'className-c-panel', self::LAYOUT )
			->add_toolbar_section( $this->layout_toolbar() )
			->add_content_section( $this->content_area() )
			->add_content_section( $this->tabs_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'tabs__header' )
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'tabs__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'tabs__description t-sink s-sink' )
					->build()
			)
			->build();
	}

	private function tabs_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'tabs__content' )
			->add_field(
				$this->factory->content()->field()->flexible_container( self::TABS )
					->set_label( __( 'Tabs', 'tribe' ) )
					->merge_nested_attributes( Tabs_Section::NAME )
					->add_template_block( Tabs_Section::NAME )
					->add_block_type( Tabs_Section::NAME )
					->set_min_blocks( 1 )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::LAYOUT_HORIZONTAL, __( 'Horizontal', 'tribe' ), 'image-flip-horizontal' )
					->add_dashicon_option( self::LAYOUT_VERTICAL, __( 'Verical', 'tribe' ), 'image-flip-vertical' )
					->set_default( self::LAYOUT_HORIZONTAL )
					->build()
			)
			->build();
	}

}
