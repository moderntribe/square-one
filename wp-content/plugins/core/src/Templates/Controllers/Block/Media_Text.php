<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Panels\Media_Text as Media_Text_Context;

class Media_Text extends Block_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Media_Text_Context::class, [
			Media_Text_Context::LAYOUT => $this->attributes[ Media_Text_Block::LAYOUT ] ?? Media_Text_Block::MEDIA_LEFT,
		] )->render();
	}
}
