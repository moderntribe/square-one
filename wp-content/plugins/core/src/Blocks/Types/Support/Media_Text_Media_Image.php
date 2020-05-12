<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;

class Media_Text_Media_Image extends Block_Type_Config {
	public const NAME = 'tribe/media-text--media-image';

	public const IMAGE = 'image';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Image' )
			->set_dashicon( 'menu-alt' )
			->set_parents( Media_Text_Media::NAME )
			->add_content_section( $this->image_area() )
			->build();
	}

	private function image_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->image( self::IMAGE )->build()
			)
			->build();
	}

}
