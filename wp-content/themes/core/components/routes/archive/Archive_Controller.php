<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\archive;

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Traits\Post_List_Field_Formatter;
use Tribe\Project\Templates\Models\Breadcrumb;
use WP_Query;

class Archive_Controller extends Abstract_Controller {
	
	use Post_List_Field_Formatter;
	
	private $sidebar_id = '';

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );
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

		
	public function get_loop_args(): array {
		$posts = [];

		$query = new WP_Query( [
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

}
