<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\loop_items\search;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Search_Controller extends Abstract_Controller {

	public function get_image_args() {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID       => (int) get_post_thumbnail_id(),
			Image_Controller::SRC_SIZE     => Image_Sizes::FOUR_THREE,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::FOUR_THREE_SMALL,
				Image_Sizes::FOUR_THREE,
				Image_Sizes::FOUR_THREE_LARGE,
			],
		];
	}

}
