<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\media_text\Media_Text_Block_Controller;

class Media_Text_Model extends Base_Model {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::MEDIA_TYPE        => $this->get_media_type(),
			Container::WIDTH             => $this->get_width(),
			Container::LAYOUT            => $this->get_layout(),
			Container::MEDIA             => $this->get_media(),
			Container::CONTENT           => $this->get_content(),
			Container::CONTAINER_CLASSES => [ 'b-media-text__container' ],
			Container::MEDIA_CLASSES     => [ 'b-media-text__media' ],
			Container::CONTENT_CLASSES   => [ 'b-media-text__content' ],
			Container::CLASSES           => [ 'c-block', 'b-media-text' ],
			Container::ATTRS             => [],
		];

		$this->render_component( 'blocks/media-text/Media_Text.php', $args );
	}

	private function get_media_type() {
		$media = reset( $this->attributes[ Media_Text_Block::MEDIA_CONTAINER ] );

		if ( isset( $media[ Media_Text_Block::IMAGE ] ) && ! empty( $media[ Media_Text_Block::IMAGE ]['id'] ) ) {
			return 'image';
		}

		if ( isset( $media[ Media_Text_Block::EMBED ] ) && ! empty( $media[ Media_Text_Block::EMBED ]['url'] ) ) {
			return 'embed';
		}

		return '';
	}

	private function get_layout(): string {
		return $this->attributes[ Media_Text_Block::LAYOUT ] ?? Media_Text_Block::MEDIA_LEFT;
	}

	private function get_width(): string {
		return $this->attributes[ Media_Text_Block::WIDTH ] ?? Media_Text_Block::WIDTH_GRID;
	}

	private function get_media(): array {
		if ( empty( $this->attributes[ Media_Text_Block::MEDIA_CONTAINER ] ) ) {
			return [];
		}

		$media = reset( $this->attributes[ Media_Text_Block::MEDIA_CONTAINER ] );

		if ( $this->get_media_type() === 'image' ) {
			return $this->get_image( $media[ Media_Text_Block::IMAGE ]['id'] );
		}

		if ( $this->get_media_type() === 'embed' ) {
			return $this->get_embed( $media[ Media_Text_Block::EMBED ]['url'] );
		}

		return [];
	}

	private function get_image( $attachment_id ): array {
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

		return [
			Image_Component::ATTACHMENT   => Image::factory( $attachment_id ),
			Image_Component::SRC_SIZE     => $src_size,
			Image_Component::SRCSET_SIZES => $srcset_sizes,
		];
	}

	private function get_embed( $url ): array {
		$content = $GLOBALS['wp_embed']->shortcode( [], $url );

		return [
			MediaTextModel::TAG  => 'div',
			MediaTextModel::TEXT => $content,
		];
	}

	private function get_content(): array {
		return [
			Content_Block::CLASSES => [ 'b-media-text__content-container' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_text(),
			Content_Block::ACTION  => $this->get_cta(),
			Content_Block::LAYOUT  => $this->get_layout() === Media_Text_Block::MEDIA_CENTER ? Content_Block::LAYOUT_INLINE : Content_Block::LAYOUT_LEFT,
		];
	}

	private function get_title(): array {
		return [
			MediaTextModel::TAG     => 'h2',
			MediaTextModel::CLASSES => [ 'b-media-text__title', 'h3' ],
			MediaTextModel::TEXT    => $this->attributes[ Media_Text_Block::TITLE ] ?? '',
		];
	}

	private function get_text(): array {
		return [
			MediaTextModel::CLASSES => [ 'b-media-text__text', 't-sink', 's-sink' ],
			MediaTextModel::TEXT    => implode( "\n", wp_list_pluck( $this->attributes[ Media_Text_Block::CONTENT ] ?? [], 'content' ) )
		];
	}

	private function get_cta(): array {
		$cta = wp_parse_args( $this->attributes['cta'] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return [];
		}

		return [
			Link::URL             => $cta['url'],
			Link::CONTENT         => $cta['text'] ?: $cta['url'],
			Link::TARGET          => $cta['target'],
			Link::CLASSES         => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			Link::WRAPPER_TAG     => 'p',
			Link::WRAPPER_CLASSES => [ 'b-media-text__cta' ],
		];
	}
}
