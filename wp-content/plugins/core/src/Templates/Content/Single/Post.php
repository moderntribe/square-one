<?php


namespace Tribe\Project\Templates\Content\Single;

use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Twig\Stringable_Callable;

class Post extends Twig_Template {
	protected $time_formats = [
		'c',
	];

	public function get_data(): array {
		$data[ 'post' ] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => new Stringable_Callable( [ $this, 'defer_get_content' ] ),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
			'time'           => $this->get_time(),
			'date'           => the_date( '', '', '', false ),
			'author'         => $this->get_author(),
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
			Image::WRAPPER_CLASSES => [ 'item-single__image' ],
		];

		return Image::factory( $options )->render();
	}

	protected function get_time() {
		$times = [];
		foreach ( $this->time_formats as $format ) {
			$times[ $format ] = get_the_time( $format );
		}

		return $times;
	}

	protected function get_author() {
		return [
			'name' => get_the_author(),
			'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		];
	}
}
