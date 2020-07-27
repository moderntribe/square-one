<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Types\Logos\Logos as Logos_Block;
use Tribe\Project\Blocks\Types\Logos\Support\Logo;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Blocks\Logos as Container;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Models\Image;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LOGOS  => $this->get_logos(),
			Container::HEADER => $this->get_header(),
		];

		$this->render_component( 'blocks/logos/Logos.php', $args );
	}

	/**
	 * @return array
	 */
	private function get_logos(): array {
		$logos = [];

		foreach ( $this->attributes[ Logos_Block::LOGOS ] as $logo_block ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $logo_block[ Logo::IMAGE ] ) ) {
				continue;
			}

			$logos[] = $this->get_single_logo( $logo_block );
		}

		return $logos;
	}

	/**
	 * @param array $logo_block
	 *
	 * @return array
	 */
	private function get_single_logo( array $logo_block ): array {
		$logo = [
			Image_Component::ATTACHMENT      => Image::factory( (int) $logo_block[ Logo::IMAGE ]['id'] ),
			Image_Component::USE_LAZYLOAD    => true,
			Image_Component::WRAPPER_CLASSES => [ 'b-logo__figure' ],
			Image_Component::IMG_CLASSES     => [ 'b-logo__img' ],
			Image_Component::SRC_SIZE        => 'large',
			Image_Component::SRCSET_SIZES    => [ 'medium', 'large' ],
		];

		$link = wp_parse_args( $logo_block[ Logo::LINK ] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( ! empty( $link['url'] ) ) {
			$logo[ Image_Component::LINK_URL ]     = $link['url'];
			$logo[ Image_Component::LINK_TARGET ]  = $link['target'];
			$logo[ Image_Component::LINK_CLASSES ] = [ 'b-logo__link' ];
			$logo[ Image_Component::LINK_ATTRS ]   = ! empty( $link['text'] ) ? [ 'aria-label' => $link['text'] ] : [];
		}

		return $logo;
	}

	private function get_header(): array {
		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'b-logos__header' ],
			Content_Block::TITLE   => $this->get_headline(),
			Content_Block::TEXT    => $this->get_text(),
			Content_Block::ACTION  => $this->get_cta(),
			Content_Block::LAYOUT  => Content_Block::LAYOUT_CENTER,
		];
	}

	private function get_headline(): array {
		return [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'b-logos__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Logos_Block::TITLE ] ?? '',
		];
	}

	private function get_text(): array {
		return [
			Text::CLASSES => [ 'b-logos__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Logos_Block::DESCRIPTION ] ?? '',
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
			Link::WRAPPER_CLASSES => [ 'b-logos__cta' ],
		];
	}
}
