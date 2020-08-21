<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\loop_items\index;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Image\Image_Controller;
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
			Image_Controller::ATTACHMENT => Image::factory( (int) get_post_thumbnail_id() ),
		] );
	}

	public function author_name(): string {
		return get_the_author();
	}

	public function author_url(): string {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
}
