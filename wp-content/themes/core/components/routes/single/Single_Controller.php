<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\single;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

class Single_Controller extends Abstract_Controller {

	use Breadcrumbs;

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
		get_template_part( 'components/document/header/header', 'single' );
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
			'single',
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
		get_template_part( 'components/document/footer/footer', 'single' );
	}

	public function get_image_args() {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID => (int) get_post_thumbnail_id(),
		];
	}

}
