<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Media_Text;

class Media_Text_Media extends Block_Type_Config {
	public const NAME = Media_Text::NAME . '--media';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Media', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Media_Text::NAME )
			->add_class( 'media-text__media' )
			->add_content_section( $this->child_container() )
			->build();
	}

	private function child_container(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->flexible_container( Media_Text::MEDIA_CONTAINER )
					->add_block_type( 'tribe/media-text--media-image' )
					->add_block_type( 'tribe/media-text--media-embed' )
					->add_template_block( 'tribe/media-text--media-image' )
					->set_max_blocks( 1 )
					->merge_nested_attributes( 'tribe/media-text--media-image' )
					->merge_nested_attributes( 'tribe/media-text--media-embed' )
					->discard_nested_content( 'tribe/media-text--media-image' )
					->discard_nested_content( 'tribe/media-text--media-embed' )
					->build()
			)
			->build();
	}

}
