<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\loop_items\index;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;

class Index_Controller extends Abstract_Controller {

	public function get_image_args() {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID => (int) get_post_thumbnail_id(),
		];
	}

}
