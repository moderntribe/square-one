<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\page\index;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Image\Image_Controller;
use Tribe\Project\Templates\Models\Image;

class Page_Controller extends Abstract_Controller {

	public function get_image_args() {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::ATTACHMENT => Image::factory( (int) get_post_thumbnail_id() ),
		];
	}

}
