<?php


namespace Tribe\Project\Templates\Controllers\Content\Single;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Content\Single;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Theme\Social_Links;

class Post extends Abstract_Template {
	protected $time_formats = [
		'c',
	];

	public function render( string $path = '' ): string {
		return $this->factory->get( Single::class, $this->get_data() )->render( $path );
	}

	public function get_data(): array {
		return [
			Single::POST_TYPE => get_post_type(),
			Single::TITLE     => get_the_title(),
			Single::CONTENT   => $this->get_content(),
			Single::EXCERPT   => apply_filters( 'the_excerpt', get_the_excerpt() ),
			Single::PERMALINK => get_the_permalink(),
			Single::IMAGE     => $this->get_featured_image(),
			Single::TIMES     => $this->get_time(),
			Single::DATE      => the_date( '', '', '', false ),
			Single::AUTHOR    => $this->get_author(),
			Single::SHARE     => $this->get_social_share(),
		];
	}

	public function get_content() {
		return apply_filters( 'the_content', get_the_content() );
	}

	protected function get_featured_image(): string {
		$image_id = get_post_thumbnail_id();

		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::WRAPPER_CLASSES => [ 'item-single__image' ],
		];

		return $this->factory->get( Image::class, $options )->render();
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

	protected function get_social_share() {
		$social = new Social_Links( [ 'facebook', 'twitter', 'linkedin', 'email' ], false );

		return $social->format_links( $social->get_links() );
	}

}
