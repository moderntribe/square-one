<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Project\Blocks\Types\Quote\Quote as Quote_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Blocks\Quote as Container;
use \Tribe\Project\Templates\Components\Quote as Quote_Component;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT  => $this->get_layout(),
			Container::MEDIA   => $this->get_media(),
			Container::CONTENT => $this->get_content(),
		];

		$this->render_component( 'blocks/quote/Quote.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Quote_Block::LAYOUT ] ?? Quote_Block::MEDIA_OVERLAY;
	}

	private function get_media(): array {
		if ( empty( $this->attributes[ Quote_Block::IMAGE ] ) ) {
			return [];
		}

		return $this->get_image( $this->attributes[ Quote_Block::IMAGE ]['id'] );
	}

	private function get_image( $attachment_id ): array {
		return [
			Image_Component::ATTACHMENT      => Image::factory( (int) $attachment_id ),
			Image_Component::AS_BG           => true,
			Image_Component::USE_LAZYLOAD    => true,
			Image_Component::WRAPPER_TAG     => 'div',
			Image_Component::WRAPPER_CLASSES => [ 'b-quote__figure' ],
			Image_Component::IMG_CLASSES     => [ 'b-quote__img', 'c-image__bg' ],
			Image_Component::SRC_SIZE        => Image_Sizes::CORE_FULL,
			Image_Component::SRCSET_SIZES    => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}

	private function get_content(): array {
		return [
			Quote_Component::CLASSES    => [ 'b-quote__content-container', 'c-quote' ],
			Quote_Component::QUOTE_TEXT => $this->attributes[ Quote_Block::QUOTE ] ?? '',
			Quote_Component::CITE_NAME  => $this->attributes[ Quote_Block::CITE_NAME ] ?? '',
			Quote_Component::CITE_TITLE => $this->attributes[ Quote_Block::CITE_TITLE ] ?? '',
			Quote_Component::CITE_IMAGE => $this->attributes[ Quote_Block::CITE_IMAGE ] ?? [],
		];
	}
}
