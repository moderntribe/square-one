<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Logo_Collection;

class Logos_Model extends Base_Model {

	public function init_data(): array {
		return [
			Logos_Block_Controller::ATTRS       => $this->get_attrs(),
			Logos_Block_Controller::CLASSES     => $this->get_classes(),
			Logos_Block_Controller::TITLE       => $this->get( Logos::TITLE, '' ),
			Logos_Block_Controller::LEADIN      => $this->get( Logos::LEAD_IN, '' ),
			Logos_Block_Controller::DESCRIPTION => $this->get( Logos::DESCRIPTION, '' ),
			Logos_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Logos_Block_Controller::LOGOS       => Logo_Collection::create( $this->get( Logos::LOGOS, [] ) ),
		];
	}

}
