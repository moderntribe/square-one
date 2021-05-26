<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\routes\index;

use Tribe\Project\Taxonomies\Featured\Featured;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\Traits\Post_List_Field_Formatter;
use WP_Query;

class Index_Controller extends Abstract_Controller {
	
	use Post_List_Field_Formatter;
	
	public const IS_TERM = 'is_term';

	private $is_term;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->is_term = (bool) $args[ self::IS_TERM ];
	}

	protected function defaults(): array {
		return [
			self::IS_TERM => false,
		];
	}

	/**
	 * @var int|string
	 */
	private $sidebar_id = '';

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

	public function render_breadcrumbs(): void {
		//TODO: let's make this get_breadcrumb_args() and render in template
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'index',
			[ Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs() ]
		);
	}

	/**
	 * @return Breadcrumb[]
	 */
	protected function get_breadcrumbs(): array {
		$page = get_option( 'page_for_posts' );
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, __( 'News', 'tribe' ) ),
		];
	}

	public function get_current_page(): int {
		return (int) get_query_var('paged') ?: 1;
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

		$posts = [];

		$query = new WP_Query( [
			'tax_query' => [
				[
					'taxonomy' => Featured::NAME,
					'operator' => 'EXISTS',
				]
			]
		] );

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$posts[] = $this->formatted_post( $post, 45 );
			}
		}

		return [
			Content_Loop_Controller::LAYOUT => Content_Loop::LAYOUT_FEATURE,
			Content_Loop_Controller::POSTS  => array_slice( $posts, 0, 7 ),
		];
	}

	/**
	*
	*
	* @return array
	*/
	public function get_loop_args(): array {
		$posts = [];

		while ( have_posts() ) {
			the_post();
			$posts[] = $this->formatted_post( get_post(), 22 );
		}

		
		return [
			Content_Loop_Controller::CLASSES => [ 'item-index__loop' ],
			Content_Loop_Controller::LAYOUT  => Content_Loop::LAYOUT_ROW,
		//	Content_Loop_Controller::LAYOUT  => Content_Loop::LAYOUT_COLUMNS,
			Content_Loop_Controller::POSTS   => $posts,
		
		];
	}
}
