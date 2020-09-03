<?php


namespace Tribe\Project\Blocks\Types\Buttons;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\buttons\Buttons_Block_Controller;

class Buttons_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Buttons_Block_Controller::CLASSES => $this->getClassName(),
			Buttons_Block_Controller::BUTTONS => $this->get( Buttons::BUTTONS, [] ),
		];
	}
}
