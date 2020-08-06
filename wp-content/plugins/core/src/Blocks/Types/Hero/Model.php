<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Model extends Base_Model {

	public function get_data() {
		return [
			'layout'            => $this->get_layout(),
			'media'             => $this->get_media(),
			'content'           => $this->get_content(),
			'container_classes' => [ 'b-hero__container', 'l-container' ],
			'media_classes'     => [ 'b-hero__media' ],
			'content_classes'   => [ 'b-hero__content' ],
			'calsses'           => [ 'c-block', 'c-block--full-bleed', 'b-hero' ],
			'attr'              => [],
		];

	}

	/**
	 * @return bool|mixed
	 */
	private function get_layout() {
		return $this->get( Hero::LAYOUT, Hero::LAYOUT_LEFT );
	}

	/**
	 * @return array
	 */
	private function get_media(): array {
		if ( $this->get( Hero::IMAGE ) ) {
			return [];
		}

		return $this->get_image( $this->get( Hero::IMAGE ) );
	}

	/**
	 * @param $attachment_id
	 *
	 * @return array
	 */
	private function get_image( $attachment_id ): array {
		return [
			'attachment'    => Image::factory( (int) $attachment_id ),
			'as_bg'         => true,
			'use_lazyload'  => true,
			'wrapper_tag'   => 'div',
			'wrapper_class' => [ 'b-hero__figure' ],
			'image_classes' => [ 'b-hero__img', 'c-image__bg' ],
			'src_size'      => Image_Sizes::CORE_FULL,
			'srcset_size'   => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}

	/**
	 * @return array
	 */
	private function get_content(): array {
		return [
			'classes' => [ 'b-hero__content-container', 't-theme--light' ],
			'leadin'  => $this->get_leadin(),
			'title'   => $this->get_headline(),
			'text'    => $this->get_text(),
			'action'  => $this->get_cta(),
			'layout'  => $this->get_layout(),
		];
	}

	/**
	 * @return array
	 */
	private function get_leadin(): array {
		return [
			'tag'     => 'p',
			'classes' => [ 'b-hero__leadin', 'h6' ],
			'content' => $this->get( Hero::LEAD_IN, '' ),
		];
	}

	/**
	 * @return array
	 */
	private function get_headline(): array {
		return [
			'tag'     => 'h2',
			'classes' => [ 'b-hero__title', 'h1' ],
			'content' => $this->get( Hero::TITLE, '' ),
		];
	}

	/**
	 * @return array
	 */
	private function get_text(): array {
		return [
			'classes' => [ 'b-hero__description', 't-sink', 's-sink' ],
			'content' => $this->get( Hero::DESCRIPTION, '' ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta(): array {
		$cta = wp_parse_args( $this->get( Hero::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return [];
		}

		return [
			'url'             => $cta[ 'url' ],
			'content'         => $cta[ 'title' ] ? : $cta[ 'url' ],
			'target'          => $cta[ 'target' ],
			'classes'         => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			'wrapper_tag'     => 'p',
			'wrapper_classes' => [ 'b-hero__cta' ],
		];
	}
}
