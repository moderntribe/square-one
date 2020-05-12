<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Media_Text_Media;
use Tribe\Project\Blocks\Types\Support\Media_Text_Text;

class Media_Text extends Block_Type_Config {
	public const NAME = 'tribe/media-text';

	public const LAYOUT      = 'layout';
	public const MEDIA_LEFT  = 'left';
	public const MEDIA_RIGHT = 'right';
	public const WIDTH       = 'width';
	public const WIDTH_BOXED = 'boxed';
	public const WIDTH_FULL  = 'full';
	public const CONTAINER   = 'container';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Media + Text' )
			->set_dashicon( 'menu-alt' )
			->add_content_section( $this->child_container() )
			->add_toolbar_section( $this->layout_toolbar() )
			->build();
	}

	private function child_container(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->fixed_container( self::CONTAINER )
					->add_template_block( Media_Text_Media::NAME )
					->add_template_block( Media_Text_Text::NAME )
					->merge_nested_attributes( Media_Text_Media::NAME )
					->merge_nested_attributes( Media_Text_Text::NAME )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
					->add_dashicon_option( self::MEDIA_LEFT, __( 'Media Left', 'tribe' ), 'editor-alignleft' )
					->add_dashicon_option( self::MEDIA_RIGHT, __( 'Media Right', 'tribe' ), 'editor-alignright' )
					->build()
			)
			->add_field(
				$this->factory->toolbar()->field()->icon_select( self::WIDTH )
					->add_dashicon_option( self::WIDTH_BOXED, __( 'Boxed', 'tribe' ), 'editor-contract' )
					->add_dashicon_option( self::WIDTH_FULL, __( 'Full', 'tribe' ), 'editor-expand' )
					->build()
			)
			->build();
	}

}
