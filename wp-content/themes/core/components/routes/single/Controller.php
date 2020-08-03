<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\routes\single;

use Tribe\Project\Templates\Factory_Method;
use Tribe\Project\Templates\Models\Breadcrumb;

class Controller {
	use Factory_Method;

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
		get_template_part( 'components/document/header/header', 'single' );
	}

	/**
	 * Render the featured image component
	 *
	 * @return void
	 */
	public function render_featured_image(): void {
		// TODO: use image component
		echo get_the_post_thumbnail();
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
		get_template_part( 'components/sidebar/sidebar', 'single', [ 'sidebar_id' => $this->sidebar_id ] );
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
		get_template_part( 'components/document/footer/footer', 'single' );
	}

	public function render_breadcrumbs(): void {
		get_template_part( 'components/breadcrumbs/breadcrumbs', 'single', [ 'breadcrumbs' => $this->get_breadcrumbs() ] );
	}

	/**
	 * @return Breadcrumb[]
	 */
	protected function get_breadcrumbs(): array {
		$page = get_the_ID();
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, get_the_title( $page ) ),
		];
	}
}
