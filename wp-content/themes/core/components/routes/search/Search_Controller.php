<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\routes\search;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use \Tribe\Project\Templates\Components\search_form\Search_Form_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Search_Controller extends Abstract_Controller {

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
	 * @return array
	 */
	public function get_search_form_args(): array {
		$args = [
			Search_Form_Controller::CLASSES => [ 'c-search--full', 'search-results__form' ],
			Search_Form_Controller::FORM_ID => uniqid( 's-' ),
			Search_Form_Controller::VALUE   => get_search_query(),
		];

		return $args;
	}

	/**
	 * @return array
	 */
	public function get_results_text_args(): array {
		global $wp_query;
		$total      = absint( $wp_query->found_posts );
		$query_term = get_search_query();

		if ( empty( $query_term ) ) {
			return [];
		}

		$text = sprintf(
			_n( 'Showing %d result for &lsquo;%s&rsquo;', 'Showing %d results for &lsquo;%s&rsquo;', $total, 'tribe' ),
			$total,
			$query_term
		);

		if ( 0 === $total ) {
			$text = sprintf(
				__( 'Your search for &lsquo;%s&rsquo; returned 0 results', 'tribe' ),
				$query_term
			);
		}

		return [
			Text_Controller::TAG     => 'p',
			Text_Controller::CLASSES => [ 'search-results__summary' ],
			Text_Controller::CONTENT => esc_html( $text ),
		];
	}
}
