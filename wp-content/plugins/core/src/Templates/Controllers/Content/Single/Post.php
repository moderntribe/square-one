<?php


namespace Tribe\Project\Templates\Controllers\Content\Single;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Theme\Social_Links;

class Post extends Abstract_Template {
	protected $time_formats = [
		'c',
	];

	public function get_data(): array {
		$data['post'] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => $this->get_content(),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
			'time'           => $this->get_time(),
			'date'           => the_date( '', '', '', false ),
			'author'         => $this->get_author(),
			'social_share'   => $this->get_social_share(),
		];

		return $data;
	}

	public function get_content() {
		return apply_filters( 'the_content', get_the_content() );
	}

	protected function get_featured_image() {
		$options = [
			'wrapper_class' => 'item-single__image',
			'echo'          => false,
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
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
