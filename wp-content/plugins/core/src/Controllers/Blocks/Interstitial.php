<?php
declare( strict_types=1 );

namespace Tribe\Project\Controllers\Blocks;

use Tribe\Project\Blocks\Types\Interstitial as Interstitial_Block;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Interstitial as Container;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Interstitial extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT            => $this->get_layout(),
			Container::MEDIA             => $this->get_media(),
			Container::CONTENT           => $this->get_content(),
			Container::CONTAINER_CLASSES => [ 'interstitial__container', 'l-container' ],
			Container::MEDIA_CLASSES     => [ 'interstitial__media' ],
			Container::CONTENT_CLASSES   => [ 'interstitial__content' ],
			Container::CLASSES           => [ 'c-panel', 'c-panel--interstitial', 'c-panel--full-bleed' ],
			Container::ATTRS             => [],
		];

		$this->render_component( 'panels/interstitial/Interstitial.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Interstitial_Block::LAYOUT ] ?? Interstitial_Block::LAYOUT_CENTER;
	}

	private function get_media(): array {
		if ( empty( $this->attributes[ Interstitial_Block::IMAGE ] ) ) {
			return [];
		}

		return $this->get_image( $this->attributes[ Interstitial_Block::IMAGE ]['id'] );
	}

	private function get_image( $attachment_id ): array {
		return [
			Image_Component::ATTACHMENT      => Image::factory( (int) $attachment_id ),
			Image_Component::AS_BG           => true,
			Image_Component::USE_LAZYLOAD    => true,
			Image_Component::WRAPPER_TAG     => 'div',
			Image_Component::WRAPPER_CLASSES => [ 'interstitial__figure' ],
			Image_Component::IMG_CLASSES     => [ 'interstitial__img', 'c-image__bg' ],
			Image_Component::SRC_SIZE        => Image_Sizes::CORE_FULL,
			Image_Component::SRCSET_SIZES    => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}

	private function get_content(): array {
		return [
			Content_Block::CLASSES => [ 'interstitial__content-container', 't-theme--light' ],
			Content_Block::TITLE   => $this->get_headline(),
			Content_Block::ACTION  => $this->get_cta(),
		];
	}

	private function get_headline(): array {
		return [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'interstitial__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Interstitial_Block::DESCRIPTION ] ?? '',
		];
	}

	private function get_cta(): array {
		$cta = wp_parse_args( $this->attributes['cta'] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link::URL             => $cta['url'],
			Link::CONTENT         => $cta['text'] ?: $cta['url'],
			Link::TARGET          => $cta['target'],
			Link::CLASSES         => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			Link::WRAPPER_TAG     => 'p',
			Link::WRAPPER_CLASSES => [ 'interstitial__cta' ],
		];
	}
}
