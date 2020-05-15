<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Panels\Media_Text as Container;
use Tribe\Project\Templates\Components\Panels\Media_Text\Content;
use Tribe\Project\Templates\Components\Panels\Media_Text\Embed;
use Tribe\Project\Templates\Components\Panels\Media_Text\Image as Image_Component;
use Tribe\Project\Templates\Models\Image;

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
			$image     = Image::factory( $attachment_id );
			$component = $this->factory->get( Image_Component::class, [
				Image_Component::IMAGE => $image,
			] );

			return $component->render();
		} catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}

	private function get_embed( $url ): string {
		return $this->factory->get( Embed::class, [
			Embed::EMBED => $GLOBALS['wp_embed']->shortcode( [], $url ),
		] )->render();
	}

	private function get_text(): string {
		$cta = $this->get_cta();

		return $this->factory->get( Content::class, [
			Content::TITLE      => $this->get_title(),
			Content::BODY       => $this->get_content(),
			Content::CTA_LABEL  => $cta['text'],
			Content::CTA_URL    => $cta['url'],
			Content::CTA_TARGET => $cta['target'],
		] )->render();
	}

	private function get_title(): string {
		return $this->attributes[ Media_Text_Block::TITLE ] ?? '';
	}

	private function get_content(): string {
		return implode( "\n", wp_list_pluck( $this->attributes[ Media_Text_Block::CONTENT ] ?? [], 'content' ) );
	}

	private function get_cta(): array {
		$cta = $this->attributes['cta'] ?? [];

		return wp_parse_args( $cta, [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );
	}
}
