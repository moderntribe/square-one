<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\single;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Single_Controller extends Abstract_Controller {

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	/**
	 * @return \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	public function get_breadcrumbs(): array {
		$page = get_the_ID();
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, get_the_title( $page ) ),
		];
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
