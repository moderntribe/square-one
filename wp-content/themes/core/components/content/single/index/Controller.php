<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\single\index;

use Tribe\Project\Templates\Factory_Method;
use Tribe\Project\Templates\Models\Image;

class Controller {
	use Factory_Method;

	/**
	 * Render the featured image component
	 */
	public function render_featured_image() {
		if ( ! has_post_thumbnail() ) {
			return '';
		}
		return get_template_part( 'components/image/image', null, [
			'attachment' => Image::factory( (int) get_post_thumbnail_id() ),
		] );
	}

	public function author_name(): string {
		return get_the_author();
	}

	public function author_url(): string {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
}
