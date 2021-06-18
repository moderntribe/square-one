<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\index;

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Taxonomies\Featured\Featured;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\Traits\Post_List_Field_Formatter;
use Tribe\Project\Templates\Models\Breadcrumb;
use WP_Query;

class Index_Controller extends Abstract_Controller {

	use Post_List_Field_Formatter;

	public string $sidebar_id = '';

	/**
	 * @return \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	// TODO: This should probably be a component call rather than managed in every page controller
	protected function get_breadcrumbs(): array {
		$page = get_option( 'page_for_posts' );
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, __( 'News', 'tribe' ) ),
		];
	}

	public function get_loop_args(): array {
		global $wp_query;
		$posts = [];

		if ( ! empty( $wp_query->posts ) ) {
			foreach ( $wp_query->posts as $post ) {
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

	/**
	 * Get posts in the featured taxonomy.
	 */
	public function get_featured_posts_args(): array {

		$featured_post_query = $this->get_featured_posts();
		$featured_post_array = [];

		if ( ! $featured_post_query->have_posts() ) {
			return [];
		}

		foreach ( $featured_post_query->posts as $post ) {
			$featured_post_array[] = $this->formatted_post( $post, 45 );
		}

		return [
			Content_Loop_Controller::LAYOUT => Content_Loop::LAYOUT_FEATURE,
			Content_Loop_Controller::POSTS  => array_slice( $featured_post_array, 0, 6 ),
		];
	}

	protected function get_featured_posts(): WP_Query {
		// Get featured posts
		return new WP_Query( [
			'tax_query' => [
				[
					'taxonomy'       => Featured::NAME,
					'operator'       => 'EXISTS',
					'posts_per_page' => '7',
				],
			],
		] );
	}

	// TODO: This should be a utility method somewhere
	public function get_current_page(): int {
		return (int) get_query_var( 'paged' ) ?: 1;
	}

}
