<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\index;

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Taxonomies\Featured\Featured;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Traits\Post_List_Field_Formatter;
use Tribe\Project\Templates\Models\Breadcrumb;
use WP_Query;

class Index_Controller extends Abstract_Controller {
	
	use Post_List_Field_Formatter;
	
	public const IS_TERM = 'is_term';

	private bool $is_term;
	private array $featured_posts_id = [ '0' ];
	private $sidebar_id              = '';

	public function __construct( array $args = [] ) {
		$args          = $this->parse_args( $args );
		$this->is_term = (bool) $this->is_term();
	}

	protected function defaults(): array {
		return [
			self::IS_TERM => false,
		];
	}


	/**
	 * Render the header component
	 *
	 * Bypasses the get_header() function to
	 * load our component instead of header.php
	 *
	 * @return void
	 */
	public function render_header(): void {
		do_action( 'get_header', null );
		get_template_part( 'components/document/header/header', 'index' );
	}


	/**
	 * Render the subheader component
	 *
	 * @return void
	 */
	public function render_subheader(): void {
		get_template_part( 'components/header/subheader_archive/subheader_archive', 'index' );
	}


	/**
	 * Render the sidebar component
	 *
	 * Bypasses the get_sidebar() function to
	 * load our component instead of sidebar.php
	 *
	 * @return void
	 */
	public function render_sidebar(): void {
		do_action( 'get_sidebar', null );
		get_template_part(
			'components/sidebar/sidebar',
			'index',
			[ Sidebar_Controller::SIDEBAR_ID => $this->sidebar_id ]
		);
	}

	/**
	 * Render the footer component
	 *
	 * Bypasses the get_footer() function to
	 * load our component instead of footer.php
	 *
	 * @return void
	 */
	public function render_footer(): void {
		do_action( 'get_footer', null );
		get_template_part( 'components/document/footer/footer', 'index' );
	}

	/**
	 * Render the breadcrums component
	 *
	 * @return void
	 */
	public function render_breadcrumbs(): void {
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'index',
			[ Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs() ]
		);
	}

	/**
	 * @return \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	protected function get_breadcrumbs(): array {
		$page = get_option( 'page_for_posts' );
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, __( 'News', 'tribe' ) ),
		];
	}

	/**
	 * @return int
	 */
	public function is_term(): int {
		if ( is_home() ) {
			return 0;
		}

		return (int) get_queried_object()->term_id ?: 0;
	}


	/**
	 * @return int
	 */
	public function get_current_page(): int {
		return (int) get_query_var( 'paged' ) ?: 1;
	}


	/**
	 * @return void
	 */
	public function get_number_of_posts() {
		return 
		[
			Text_Controller::TAG     => 'p',
			Text_Controller::CLASSES => [ '' ],
			Text_Controller::CONTENT => wp_count_posts()->publish.' '.__( 'posts in', 'tribe' ).' "'.get_the_archive_title().'" ',
			
		];
	}

	/**
	* Get posts in the featured taxonomy.
	*
	* @return array
	*/
	public function get_featured_posts_args(): array {
		if ( $this->is_term ) {
			return [];
		}

		$featured_posts = [];
		
		$query = new WP_Query( [
			'tax_query' => [
				[
					'taxonomy' => Featured::NAME,
					'operator' => 'EXISTS',
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

	/**
	 * @return array
	*/
	public function get_loop_args(): array {
		$posts = [];

		/* to exclude featured if we are displaying them */
		$query = new WP_Query( [
			'post__not_in' => array_slice( $this->featured_posts_id, 0, 7 ),
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

}
