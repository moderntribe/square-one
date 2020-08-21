<?php


namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\links\Links_Block_Controller;

class Links_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Links_Block_Controller::LINKS       => $this->get( Links::LINKS, [] ),
			Links_Block_Controller::LINKS_TITLE => $this->get( Links::LINKS_TITLE, '' ),
			Links_Block_Controller::TITLE       => $this->get( Links::TITLE, '' ),
			Links_Block_Controller::DESCRIPTION => $this->get( Links::DESCRIPTION, '' ),
			Links_Block_Controller::LAYOUT      => $this->get(
				Links::LAYOUT,
				Links::LAYOUT_STACKED
			),

		];
	}

}
