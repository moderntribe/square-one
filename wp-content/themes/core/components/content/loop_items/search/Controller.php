<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\loop_items\search;

use Tribe\Project\Templates\Factory_Method;
use Tribe\Project\Templates\Models\Image;

class Controller {
	use Factory_Method;

	/**
	 * Render the featured image component
	 *
	 * @return void
	 */
	public function render_featured_image(): void {
		get_template_part( 'components/image/image', null, [
			'attachment' => Image::factory( (int) get_post_thumbnail_id() ),
		] );
	}
}
