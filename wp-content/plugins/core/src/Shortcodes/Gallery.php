<?php

namespace Tribe\Project\Shortcodes;

use Tribe\Project\Templates\Components\Controller;

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
		$atts = shortcode_atts( [
			'order'           => 'ASC',
			'orderby'         => 'menu_order ID',
			'id'              => $post ? $post->ID : 0,
			'include'         => '',
			'exclude'         => '',
			'show_carousel'   => true,
			'show_arrows'     => true,
			'show_pagination' => false,
		], $attr, 'gallery' );

		$attachments = $this->get_attachments( $atts );

		if ( empty( $attachments ) ) {
			return '';
		}

		// TODO: work with new component system
		return '';
		$options = [
			Controller::SLIDES          => $this->get_slides( $attachments ),
			Controller::THUMBNAILS      => $this->get_slides( $attachments, 'thumbnail' ),
			Controller::SHOW_CAROUSEL   => $atts['show_carousel'],
			Controller::SHOW_ARROWS     => $atts['show_arrows'],
			Controller::SHOW_PAGINATION => $atts['show_pagination'],
			Controller::MAIN_CLASSES    => [],
		];

		return $this->component->get( Controller::class, $options )->get_rendered_output();
	}

	protected function get_attachments( $atts ) {
		$id = (int) $atts['id'];

		if ( ! empty( $atts['include'] ) ) {
			$attachments = get_posts( [
				'include'        => $atts['include'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
				'fields'         => 'ids',
			] );
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( [
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
				'fields'         => 'ids',
			] );
		} else {
			$attachments = get_children( [
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
				'fields'         => 'ids',
			] );
		}

		return $attachments;
	}

	protected function get_slides( $slide_ids, $size = 'full' ): array {
		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_filter( array_map( function ( $slide_id ) use ( $size ) {
			// TODO: work with new component system
			return '';
			try {
				$image = new \Tribe\Project\Models\Image( $slide_id );
			} catch ( \Exception $e ) {
				return '';
			}
			$options = [
				Image::ATTACHMENT   => $image,
				Image::AS_BG        => false,
				Image::USE_LAZYLOAD => false,
				Image::SRC_SIZE     => $size,
			];

			return $this->component->get( Image::class, $options )->get_rendered_output();
		}, $slide_ids ) );
	}
}
