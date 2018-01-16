<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\PostLoop as PostLoopPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Theme\Image_Sizes;

class PostLoop extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title( PostLoopPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description' => ! empty( $this->panel_vars[ PostLoopPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ PostLoopPanel::FIELD_DESCRIPTION ] : false,
			'posts'       => $this->get_posts(),
		];

		return $data;
	}

	protected function get_posts(): array {
		$posts = [];

		if ( ! empty( $this->panel_vars[ PostLoopPanel::FIELD_POSTS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ PostLoopPanel::FIELD_POSTS ] ); $i ++ ) {

				$post = $this->panel_vars[ PostLoopPanel::FIELD_POSTS ][ $i ];

				$options = [
					Card::TITLE     => esc_html( get_the_title( $post[ 'post_id' ] ) ),
					Card::IMAGE     => $this->get_post_image( get_post_thumbnail_id( $post[ 'post_id' ] ) ),
					Card::PRE_TITLE => get_the_category_list( '', '', $post[ 'post_id' ] ),
					Card::BUTTON    => $this->get_post_button( $post[ 'post_id' ] ),
				];

				$post_obj = Card::factory( $options );
				$posts[]  = $post_obj->render();
			}
		}

		return $posts;
	}

	protected function get_post_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return false;
		}

		$options = [
			Image::IMG_ID       => $image_id,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::ECHO         => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_post_button( $post_id ) {
		$options = [
			Button::URL    => esc_url( get_the_permalink( $post_id ) ),
			Button::LABEL  => __( 'View Post', 'tribe' ),
			Button::TARGET => '_self',
		];

		$button_obj = Button::factory( $options );

		return $button_obj->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/postloop'];
	}
}
