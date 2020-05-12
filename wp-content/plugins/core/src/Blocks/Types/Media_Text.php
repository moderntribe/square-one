<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Supports\Align;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Media_Text_Media;
use Tribe\Project\Blocks\Types\Support\Media_Text_Text;

class Media_Text extends Block_Type_Config {
	public const NAME = 'tribe/media-text';

	public const LAYOUT      = 'layout';
	public const MEDIA_LEFT  = 'media-left';
	public const MEDIA_RIGHT = 'media-right';
	public const CONTAINER   = 'container';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Media + Text' )
			->set_dashicon( 'menu-alt' )
			->supports_align( [ Align::WIDE, Align::FULL ] )
			->add_content_section( $this->child_container() )
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

}
