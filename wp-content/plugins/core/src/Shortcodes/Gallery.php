<?php

namespace Tribe\Project\Shortcodes;

use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Slider;

class Gallery implements Shortcode {

	/**
	 * Render the [gallery] shortcode when placed in content areas.
	 *
	 * @filter post_gallery 10 3
	 *
	 * @param array $attr
	 * @param int   $instance
	 *
	 * @return string
	 */
	public function render( array $attr, int $instance ): string {
		$post = get_post();
		$atts = shortcode_atts(
			[
				'order'           => 'ASC',
				'orderby'         => 'menu_order ID',
				'id'              => $post ? $post->ID : 0,
				'include'         => '',
				'exclude'         => '',
				'show_carousel'   => true,
				'show_arrows'     => true,
				'show_pagination' => false,
			], $attr, 'gallery'
		);

		$attachments = $this->get_attachments( $atts );

		if ( empty( $attachments ) ) {
			return '';
		}

		$options = [
			Slider::SLIDES          => $this->get_slides( $attachments ),
			Slider::THUMBNAILS      => $this->get_slides( $attachments, 'thumbnail' ),
			Slider::SHOW_CAROUSEL   => $atts['show_carousel'],
			Slider::SHOW_ARROWS     => $atts['show_arrows'],
			Slider::SHOW_PAGINATION => $atts['show_pagination'],
			Slider::MAIN_CLASSES    => [],
		];

		$slider = Slider::factory( $options );

		return $slider->render();
	}

	protected function get_attachments( $atts ) {
		$id = (int) $atts['id'];

		if ( ! empty( $atts['include'] ) ) {
			$attachments = get_posts(
				[
					'include'        => $atts['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
					'fields'         => 'ids',
				]
			);
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children(
				[
					'post_parent'    => $id,
					'exclude'        => $atts['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
					'fields'         => 'ids',
				]
			);
		} else {
			$attachments = get_children(
				[
					'post_parent'    => $id,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
					'fields'         => 'ids',
				]
			);
		}

		return $attachments;
	}

	protected function get_slides( $slide_ids, $size = 'full' ): array {
		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map(
			function ( $slide_id ) use ( $size ) {
					$options = [
						'img_id'       => $slide_id,
						'as_bg'        => false,
						'use_lazyload' => false,
						'echo'         => false,
						'src_size'     => $size,
					];

					$image = Image::factory( $options );

					return $image->render();
			}, $slide_ids
		);
	}
}
