<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Media_Text\Support\Media_Text_Media;
use Tribe\Project\Blocks\Types\Media_Text\Support\Media_Text_Text;

class Media_Text extends Block_Type_Config {
	public const NAME = 'tribe/media-text';

	public const LAYOUT       = 'layout';
	public const MEDIA_LEFT   = 'left';
	public const MEDIA_RIGHT  = 'right';
	public const MEDIA_CENTER = 'center';
	public const WIDTH        = 'width';
	public const WIDTH_GRID   = 'grid';
	public const WIDTH_FULL   = 'full';
	public const CONTAINER    = 'container';

	public const TITLE   = 'title';
	public const CONTENT = 'content';
	public const CTA     = 'cta';

	public const MEDIA_CONTAINER = 'media';
	public const IMAGE           = 'image';
	public const EMBED           = 'embed';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Media + Text', 'tribe' ) )
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
					->add_dashicon_option( self::MEDIA_CENTER, __( 'Media Center', 'tribe' ), 'editor-aligncenter' )
					->add_dashicon_option( self::MEDIA_RIGHT, __( 'Media Right', 'tribe' ), 'editor-alignright' )
					->set_default( self::MEDIA_LEFT )
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
