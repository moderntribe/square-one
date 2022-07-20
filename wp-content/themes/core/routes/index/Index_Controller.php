<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\index;

use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Libs\Taxonomy\Term_Object;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Object_Meta\Post_Archive_Featured_Settings;
use Tribe\Project\Object_Meta\Post_Archive_Settings;
use Tribe\Project\Object_Meta\Taxonomy_Archive_Settings;
use Tribe\Project\Settings\Post_Settings;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;
use Tribe\Project\Templates\Components\Traits\With_Pagination_Helper;
use WP_Term;

class Index_Controller extends Abstract_Controller {

	use Page_Title;
	use With_Pagination_Helper;

	public const SIDEBAR_ID = 'sidebar_id';

	protected string $sidebar_id = '';
	protected Post_Settings $post_settings;
	protected Post_Loop_Controller $post_loop;

	public function __construct( Post_Settings $post_settings, Post_Loop_Controller $post_loop, array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->sidebar_id    = (string) $args[ self::SIDEBAR_ID ];
		$this->post_settings = $post_settings;
		$this->post_loop     = $post_loop;
	}

	public function get_sidebar_id(): string {
		return $this->sidebar_id;
	}

	/**
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function get_content_loop_args(): array {
		global $wp_query;

		/** change LAYOUT to Content_Loop::LAYOUT_ROW to get one post per row */
		return [
			Content_Loop_Controller::CLASSES => [ 'item-index__loop' ],
			Content_Loop_Controller::LAYOUT  => Content_Loop::LAYOUT_COLUMNS,
			Content_Loop_Controller::POSTS   => $this->post_loop->proxy_posts( $wp_query->posts ),
		];
	}

	/**
	 * Get posts in the featured posts from the Post Archive Settings page.
	 *
	 * @param int $max_posts The maximum number of featured posts to display.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return array
	 */
	public function get_content_loop_featured_args( int $max_posts = 6 ): array {
		// @phpstan-ignore-next-line
		$featured_post_query = $this->post_settings->get_setting( Post_Archive_Featured_Settings::FEATURED_POSTS, [] );

		if ( empty( $featured_post_query ) ) {
			return [];
		}

		return [
			Content_Loop_Controller::LAYOUT            => Content_Loop::LAYOUT_FEATURE,
			Content_Loop_Controller::POSTS             => $this->post_loop->proxy_posts( array_slice( $featured_post_query, 0, $max_posts ) ),
			Content_Loop_Controller::ENABLE_PAGINATION => false,
		];
	}

	/**
	 * Prepare data for the Subheader Component.
	 *
	 * @see \Tribe\Project\Templates\Components\header\subheader\Subheader_Controller
	 *
	 * @return array
	 */
	public function get_subheader_args(): array {
		$args = [];

		if ( is_category() || is_tag() ) {
			return $this->get_subheader_core_taxonomy_args();
		}

		// Manually Set via Posts > Settings.
		if ( is_front_page() ) {
			return $this->get_subheader_front_page_args();
		}

		// Default
		$args[ Subheader_Controller::TITLE ] = $this->get_page_title();

		return $args;
	}

	protected function get_subheader_core_taxonomy_args(): array {
		$term = get_queried_object();

		if ( ! $term instanceof WP_Term ) {
			return [];
		}

		$taxonomy = Term_Object::factory( $term->term_id );

		return array_filter( [
			Subheader_Controller::TITLE       => $term->name,
			Subheader_Controller::DESCRIPTION => $term->description,
			Subheader_Controller::HERO_IMAGE  => new Image( (array) $taxonomy->get_meta( Taxonomy_Archive_Settings::HERO_IMAGE ) ),
		] );
	}

	protected function get_subheader_front_page_args(): array {
		return array_filter( [
			Subheader_Controller::TITLE       => $this->post_settings->get_setting( Post_Archive_Settings::TITLE, '' ),
			Subheader_Controller::DESCRIPTION => $this->post_settings->get_setting( Post_Archive_Settings::DESCRIPTION, '' ),
			Subheader_Controller::HERO_IMAGE  => new Image( $this->post_settings->get_setting( Post_Archive_Settings::HERO_IMAGE, [] ) ),
		] );
	}

	protected function defaults(): array {
		return [
			self::SIDEBAR_ID => '',
		];
	}

}
