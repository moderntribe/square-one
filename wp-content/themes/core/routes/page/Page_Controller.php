<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\page;

use Tribe\Project\Templates\Components\header\subheader\Subheader_Single_Controller;
use Tribe\Project\Templates\Routes\single\Single_Controller;

class Page_Controller extends Single_Controller {

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	public function get_subheader_args(): array {

		$args[ Subheader_Single_Controller::TITLE ] = $this->get_page_title();

		return $args;
	}

}
