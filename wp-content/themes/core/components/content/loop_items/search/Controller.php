<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\loop_items\search;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Image;

class Controller extends Abstract_Controller {

	/**
	 * Render the featured image component
	 */
	public function render_featured_image() {
		if ( ! has_post_thumbnail() ) {
			return '';
		}

		return tribe_template_part( 'components/image/image', null, [
			'attachment' => Image::factory( (int) get_post_thumbnail_id() ),
		] );
	}
}
