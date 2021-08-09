<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\single;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Single_Controller extends Abstract_Controller {

	use Page_Title;

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	public function get_subheader_args(): array {
		return [
			Subheader_Controller::TITLE         => $this->get_page_title(),
			Subheader_Controller::HERO_IMAGE_ID => $this->get_image_id(),
		];
	}

	public function get_image_id(): int {
		return (int) get_post_thumbnail_id();
	}

}
