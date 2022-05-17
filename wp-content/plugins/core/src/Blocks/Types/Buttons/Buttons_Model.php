<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Buttons;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\buttons\Buttons_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Button_Collection;

class Buttons_Model extends Base_Model {

	public function get_data(): array {
		return [
			Buttons_Block_Controller::ATTRS   => $this->get_attrs(),
			Buttons_Block_Controller::CLASSES => $this->get_classes(),
			Buttons_Block_Controller::BUTTONS => Button_Collection::create( $this->get( Buttons::BUTTONS, [] ) ),
		];
	}

}
