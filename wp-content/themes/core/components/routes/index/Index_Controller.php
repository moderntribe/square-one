<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\index;

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Taxonomies\Featured\Featured;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\routes\archive\Archive_Controller;
use WP_Query;

class Index_Controller extends Archive_Controller {

	public array $featured_posts_id = [];

	/**
	* Get posts in the featured taxonomy.
	 */
	public function get_featured_posts_args(): array {

		$featured_posts = [];

		$query = new WP_Query( [
			'tax_query' => [
				[
					'taxonomy'       => Featured::NAME,
					'operator'       => 'EXISTS',
					'posts_per_page' => '7',
				],
			],
		] );

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$featured_posts[]          = $this->formatted_post( $post, 45 );
				$this->featured_posts_id[] = $post->ID;
			}
		}

		$this->featured_posts_id = array_slice( $this->featured_posts_id, 0, 7 );

		return [
			Content_Loop_Controller::LAYOUT => Content_Loop::LAYOUT_FEATURE,
			Content_Loop_Controller::POSTS  => array_slice( $featured_posts, 0, 6 ),
		];
	}

	public function get_loop_args(): array {
		$posts = [];

		/* to exclude featured if we are displaying them */
		$query = new WP_Query( [
			'post__not_in'   => $this->featured_posts_id,
			'posts_per_page' => '9',
		] );

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$posts[] = $this->formatted_post( $post, 45 );
			}
		}

		/** change LAYOUT to Content_Loop::LAYOUT_ROW to get one post per row */
		return [
			Content_Loop_Controller::CLASSES => [ 'item-index__loop' ],
			Content_Loop_Controller::LAYOUT  => Content_Loop::LAYOUT_COLUMNS,
			Content_Loop_Controller::POSTS   => $posts,

		];
	}

	public function get_current_page(): int {
		return (int) get_query_var( 'paged' ) ?: 1;
	}

}
