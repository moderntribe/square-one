<?php

namespace Tribe\Project\Templates;

use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Twig\Stringable_Callable;

class Page extends Base {
	public function get_data(): array {
		$data               = parent::get_data();
		$data[ 'post' ]     = $this->get_post();
		$data[ 'comments' ] = $this->get_comments();
		$data[ 'sidebar' ]  = $this->get_sidebar();

		return $data;
	}

	protected function get_post() {
		the_post();
		return [
			'content'        => new Stringable_Callable( [ $this, 'defer_get_content' ] ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];
	}

	public function defer_get_content() {
		return apply_filters( 'the_content', get_the_content() );
	}

	protected function get_featured_image(): string {
		$image_id = get_post_thumbnail_id();

		if ( empty( $image_id ) ) {
			return '';
		}

		$options = [
			Image::IMG_ID          => $image_id,
			Image::WRAPPER_CLASSES => [ 'page__image' ],
		];

		return Image::factory( $options )->render();
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
	}

	protected function get_sidebar() {
		$sidebar = new Sidebar( $this->template, $this->twig, 'sidebar-main' );
		$data = $sidebar->get_data();
		return $data[ 'sidebar' ];
	}

}
