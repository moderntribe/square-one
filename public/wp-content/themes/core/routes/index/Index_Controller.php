<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\index;

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Object_Meta\Post_Archive_Featured_Settings;
use Tribe\Project\Object_Meta\Post_Archive_Settings;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;
use Tribe\Project\Templates\Components\Traits\Post_List_Field_Formatter;

class Index_Controller extends Abstract_Controller {

	use Post_List_Field_Formatter;
	use Page_Title;

	public string $sidebar_id = '';

	public function get_content_loop_args(): array {
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
	 * Get posts in the featured posts from the Post Archive Settings page
	 */
	public function get_content_loop_featured_args(): array {

		$featured_post_query = get_field( Post_Archive_Featured_Settings::FEATURED_POSTS, 'option' );
		$featured_post_array = [];

		if ( empty( $featured_post_query ) ) {
			return [];
		}

		foreach ( $featured_post_query as $post ) {
			$featured_post_array[] = $this->formatted_post( $post, 45 );
		}

		return [
			Content_Loop_Controller::LAYOUT => Content_Loop::LAYOUT_FEATURE,
			Content_Loop_Controller::POSTS  => array_slice( $featured_post_array, 0, 6 ),
		];
	}

	// TODO: This should be a utility method somewhere
	public function get_current_page(): int {
		return (int) get_query_var( 'paged' ) ?: 1;
	}

	/**
	 * Prepare data for the Subheader Component
	 *
	 * @see \Tribe\Project\Templates\Components\header\subheader\Subheader_Controller
	 *
	 * @return array
	 */
	public function get_subheader_args(): array {
		$args = [];

		if ( is_category() ) {
			$term = get_queried_object();

			if ( ! empty( $term ) ) {
				$args[ Subheader_Controller::TITLE ]       = $term->name;
				$args[ Subheader_Controller::DESCRIPTION ] = $term->category_description;

				$hero_image = get_field( Post_Archive_Settings::HERO_IMAGE, $term->taxonomy.'_'.$term->term_id );
				if ( ! empty( $hero_image ) ) {
					$args[ Subheader_Controller::HERO_IMAGE_ID ] = $hero_image['ID'];
				}
			}

			return $args;
		}

		// Manually Set via Post Archive Settings
		if ( is_front_page() ) {
			$title = get_field( 'title', 'option' );
			if ( ! empty( $title ) ) {
				$args[ Subheader_Controller::TITLE ] = $title;
			}
			$description = get_field( 'description', 'option' );
			if ( ! empty( $description ) ) {
				$args[ Subheader_Controller::DESCRIPTION ] = $description;
			}

			$hero_image = get_field( 'hero_image', 'option' );
			if ( ! empty( $hero_image ) ) {
				$args[ Subheader_Controller::HERO_IMAGE_ID ] = $hero_image['ID'];
			}

			return $args;
		}

		// Default
		$args[ Subheader_Controller::TITLE ] = $this->get_page_title();

		return $args;
	}

}
