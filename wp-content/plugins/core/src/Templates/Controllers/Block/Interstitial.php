<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Interstitial as Interstitial_Block;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Interstitial as Container;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Interstitial extends Block_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Container::class, [
			Container::LAYOUT  => $this->get_layout(),
			Container::MEDIA   => $this->get_media(),
			Container::CONTENT => $this->get_content(),
		] )->render();
	}

	private function get_layout(): string {
		return $this->attributes[ Interstitial_Block::LAYOUT ] ?? Interstitial_Block::LAYOUT_CENTER;
	}

	private function get_media(): string {
		if ( empty( $this->attributes[ Interstitial_Block::IMAGE ] ) ) {
			return '';
		}

		return $this->get_image( $this->attributes[ Interstitial_Block::IMAGE ]['id'] );
	}

	private function get_image( $attachment_id ): string {
		try {
			return $this->factory->get( Image_Component::class, [
				Image_Component::ATTACHMENT      => Image::factory( (int) $attachment_id ),
				Image_Component::AS_BG           => true,
				Image_Component::USE_LAZYLOAD    => false, // TEMP until we get lazyload in the block editor preview.
				Image_Component::WRAPPER_TAG     => 'div',
				Image_Component::WRAPPER_CLASSES => [ 'interstitial__figure' ],
				Image_Component::IMG_CLASSES     => [ 'interstitial__img', 'c-image__bg' ],
				Image_Component::SRC_SIZE        => Image_Sizes::CORE_FULL,
				Image_Component::SRCSET_SIZES    => [
					Image_Sizes::CORE_FULL,
					Image_Sizes::CORE_MOBILE,
				],
			] )->render();
		} catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}

	private function get_content(): string {
		return $this->factory->get( Content_Block::class, [
			Content_Block::CLASSES => [ 'interstitial__content-container', 't-theme--light' ],
			Content_Block::TITLE   => $this->get_headline(),
			Content_Block::ACTION  => $this->get_cta(),
		] )->render();
	}

	private function get_headline(): string {
		if ( empty($this->attributes[ Interstitial_Block::DESCRIPTION ] ) ) {
			return '';
		}

		return $this->factory->get( Text::class, [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'interstitial__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Interstitial_Block::DESCRIPTION ],
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
			Text::CLASSES => [ 'interstitial__cta' ],
			Text::TEXT => $cta_html,
		] )->render();
	}
}
