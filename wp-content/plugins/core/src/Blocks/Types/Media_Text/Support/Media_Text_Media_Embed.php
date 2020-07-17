<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Media_Text\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text;

class Media_Text_Media_Embed extends Block_Type_Config {
	public const NAME = Media_Text::NAME . '--media-embed';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Video', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Media_Text_Media::NAME )
			->add_content_section( $this->image_area() )
			->build();
	}

	private function image_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->embed( Media_Text::EMBED )->build()
			)
			->build();
	}

}
