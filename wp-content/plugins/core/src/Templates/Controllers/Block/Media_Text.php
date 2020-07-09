<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Container as Container_Component;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Media_Text as Container;
use Tribe\Project\Templates\Components\Text;
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

		$src_size     = Image_Sizes::FOUR_THREE;
		$srcset_sizes = [
			Image_Sizes::FOUR_THREE_SMALL,
			Image_Sizes::FOUR_THREE,
			Image_Sizes::FOUR_THREE_LARGE,
		];

		if ( $this->get_layout() === Media_Text_Block::MEDIA_CENTER ) {
			$src_size     = Image_Sizes::SIXTEEN_NINE;
			$srcset_sizes = [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_LARGE,
			];
		}

		try {
			return $this->factory->get( Image_Component::class, [
				Image_Component::ATTACHMENT   => Image::factory( $attachment_id ),
				Image_Component::SRC_SIZE     => $src_size,
				Image_Component::SRCSET_SIZES => $srcset_sizes,
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
			Content_Block::CLASSES => [ 'media-text__content-container' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_content(),
			Content_Block::ACTION  => $this->get_cta(),
		] )->render();
	}

	private function get_title(): string {
		if ( empty($this->attributes[ Media_Text_Block::TITLE ] ) ) {
			return '';
		}

		return $this->factory->get( Text::class, [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'media-text__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Media_Text_Block::TITLE ],
		] )->render();
	}

	private function get_content(): string {
		return $this->factory->get( Container_Component::class, [
			Container_Component::TAG => 'div',
			Container_Component::CLASSES => [ 'media-text__text', 't-sink', 's-sink' ],
			Container_Component::CONTENT => implode( "\n", wp_list_pluck( $this->attributes[ Media_Text_Block::CONTENT ] ?? [], 'content' ) )
		] )->render();
	}

	private function get_cta(): string {
		$cta = wp_parse_args( $this->attributes['cta'] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return '';
		}

		$cta_html = $this->factory->get( Link::class, [
			Link::URL        => $cta['url'],
			Link::CONTENT    => $cta['text'] ?: $cta['url'],
			Link::TARGET     => $cta['target'],
			Link::CLASSES    => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
		] )->render();

		return $this->factory->get( Text::class, [
			Text::TAG => 'p',
			Text::CLASSES => [ 'media-text__cta' ],
			Text::TEXT => $cta_html,
		] )->render();
	}
}
