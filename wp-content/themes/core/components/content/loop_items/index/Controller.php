<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\loop_items\index;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function author_name(): string {
		return get_the_author();
	}

	public function author_url(): string {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
}
