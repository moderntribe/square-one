<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Header;

use Tribe\Project\Templates\Abstract_Template;

class Subheader extends Abstract_Template {
	protected $path = 'content/header/sub.twig';

	public function get_data(): array {
		return [
			'post' => [
				'title' => $this->get_title(),
			],
		];
	}

	protected function get_title() {
		return get_the_title();
	}

}
