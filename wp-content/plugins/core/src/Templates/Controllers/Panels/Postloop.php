<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Exception;
use Tribe\Project\Panels\Types\PostLoop as PostLoopPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Panels\Postloop as Postloop_Context;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Theme\Config\Image_Sizes;

class Postloop extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Postloop_Context::class, [
			Postloop_Context::POSTS      => $this->get_posts( $panel, $panel_vars ),
			Postloop_Context::ATTRIBUTES => $this->get_postloop_attributes( $panel ),
		] )->render();
	}

	protected function get_postloop_attributes( \ModularContent\Panel $panel ): array {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs['data-depth']    = $panel->get_depth();
			$attrs['data-name']     = PostLoopPanel::FIELD_POSTS;
			$attrs['data-index']    = '0';
			$attrs['data-livetext'] = 'true';
		}

		return $attrs;
	}

	protected function get_posts( \ModularContent\Panel $panel, array $panel_vars ): array {
		$depth = $panel->get_depth();
		$posts = [];

		foreach ( $panel_vars[ PostLoopPanel::FIELD_POSTS ] as $index => $post ) {
			$options = [
				Card::POST_TITLE => $this->get_post_title( esc_html( $post['title'] ), $index, $depth ),
				Card::IMAGE      => $this->get_post_image( $post['image'] ),
				Card::PRE_TITLE  => get_the_category_list( '', '', $post['post_id'] ),
				Card::BUTTON     => $this->get_post_button( $post['link'] ),
			];

			$posts[] = $this->factory->get( Card::class, $options )->render();
		}

		return $posts;
	}

	protected function get_post_title( $title, $index, $depth ): string {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $depth,
				'data-name'     => $title,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Title::TITLE   => $title,
			Title::CLASSES => [],
			Title::ATTRS   => $attrs,
			Title::TAG     => 'h5',
		];

		return $this->factory->get( Title::class, $options )->render();
	}

	protected function get_post_image( $image_id ): string {
		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT   => $image,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_post_button( $post_link ): string {
		$options = [
			Button::URL         => $post_link['url'],
			Button::LABEL       => __( 'View Post', 'tribe' ),
			Button::TARGET      => '_self',
			Button::BTN_AS_LINK => true,
			Button::CLASSES     => [ 'c-cta' ],
		];

		return $this->factory->get( Button::class, $options )->render();
	}
}
