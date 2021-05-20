<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\loop_items\layout_a;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Index_Controller extends Abstract_Controller {

	private string $list; 

	public function get_image_args() {
		if ( ! has_post_thumbnail() ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID => (int) get_post_thumbnail_id(),
		];
	}

	/**
	 * @return array
	 */
	public function get_taxonomy_args(): array {
		
		$cat = (array) get_the_category( get_the_ID() ); 
		
		return [
			Text_Controller::TAG     => 'p',
			Text_Controller::CLASSES => [ '' ],
			Text_Controller::CONTENT => $cat[0]->name,
		];
	}

}
