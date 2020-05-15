<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Media_Text as Container;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Media_Text extends Block_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Container::class, [
			Container::LAYOUT  => $this->get_layout(),
			Container::WIDTH   => $this->get_width(),
			Container::MEDIA   => $this->get_media(),
			Container::CONTENT => $this->get_text(),
		] )->render();
	}


	private function get_layout(): string {
		return $this->attributes[ Media_Text_Block::LAYOUT ] ?? Media_Text_Block::MEDIA_LEFT;
	}

	private function get_width(): string {
		return $this->attributes[ Media_Text_Block::WIDTH ] ?? Media_Text_Block::WIDTH_BOXED;
	}

	private function get_media(): string {
		if ( empty( $this->attributes[ Media_Text_Block::MEDIA_CONTAINER ] ) ) {
			return '';
		}

		$media = reset( $this->attributes[ Media_Text_Block::MEDIA_CONTAINER ] );

		if ( isset( $media[ Media_Text_Block::IMAGE ] ) && ! empty( $media[ Media_Text_Block::IMAGE ]['id'] ) ) {
			return $this->get_image( $media[ Media_Text_Block::IMAGE ]['id'] );
		}

		if ( isset( $media[ Media_Text_Block::EMBED ] ) && ! empty( $media[ Media_Text_Block::EMBED ]['url'] ) ) {
			return $this->get_embed( $media[ Media_Text_Block::EMBED ]['url'] );
		}

		return '';
	}

	private function get_image( $attachment_id ): string {
		try {
			return $this->factory->get( Image_Component::class, [
				Image_Component::ATTACHMENT   => Image::factory( $attachment_id ),
				Image_Component::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
				Image_Component::USE_LAZYLOAD => false,
			] )->render();
		} catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}

	private function get_embed( $url ): string {
		return $GLOBALS['wp_embed']->shortcode( [], $url );
	}

	private function get_text(): string {
		return $this->factory->get( Content_Block::class, [
			Content_Block::TITLE  => $this->get_title(),
			Content_Block::TEXT   => $this->get_content(),
			Content_Block::ACTION => $this->get_cta(),
		] )->render();
	}

	private function get_title(): string {
		return $this->attributes[ Media_Text_Block::TITLE ] ?? '';
	}

	private function get_content(): string {
		return implode( "\n", wp_list_pluck( $this->attributes[ Media_Text_Block::CONTENT ] ?? [], 'content' ) );
	}

	private function get_cta(): string {
		$cta = wp_parse_args( $this->attributes['cta'] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		return $this->factory->get( Link::class, [
			Link::URL        => $cta['url'],
			Link::CONTENT    => $cta['text'] ?: $cta['url'],
			Link::TARGET     => $cta['target'],
			Link::ARIA_LABEL => '', // TODO
			Link::CLASSES    => [ 'media-text__cta' ],
		] )->render();
	}
}
