<?php

namespace Tribe\Project\Templates\Content\Search;

use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Twig\Stringable_Callable;

class Search extends Twig_Template {

	public function get_data(): array {
		$data[ 'post' ] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => new Stringable_Callable( [ $this, 'defer_get_content' ] ),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];

		return $data;
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
			Image::LINK_URL        => get_permalink(),
			Image::WRAPPER_CLASSES => [ 'item__image' ],
		];

		return Image::factory( $options )->render();
	}
}
