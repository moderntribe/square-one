<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Spacer;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\spacer\Spacer_Block_Controller;

class Spacer_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Spacer_Block_Controller::ATTRS           => $this->get_attrs(),
			Spacer_Block_Controller::CLASSES         => $this->get_classes(),
			Spacer_Block_Controller::SIZE            => $this->get( Spacer::SIZE, Spacer::DEFAULT ),
			Spacer_Block_Controller::DISPLAY_OPTIONS => $this->get( Spacer::DISPLAY_OPTIONS, Spacer::ALL_SCREENS ),
		];
	}

}
