<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\accordion\Accordion_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Accordion_Row_Collection;

class Accordion_Model extends Base_Model {

	public function init_data(): array {
		return [
			Accordion_Block_Controller::ATTRS       => $this->get_attrs(),
			Accordion_Block_Controller::CLASSES     => $this->get_classes(),
			Accordion_Block_Controller::LAYOUT      => $this->get( Accordion::LAYOUT, Accordion::LAYOUT_STACKED ),
			Accordion_Block_Controller::ROWS        => Accordion_Row_Collection::create( $this->get( Accordion::ACCORDION, [] ) ),
			Accordion_Block_Controller::TITLE       => $this->get( Accordion::TITLE, '' ),
			Accordion_Block_Controller::LEADIN      => $this->get( Accordion::LEAD_IN, '' ),
			Accordion_Block_Controller::DESCRIPTION => $this->get( Accordion::DESCRIPTION, '' ),
			Accordion_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Accordion_Block_Controller::SCROLL_TO   => $this->get( Accordion::SCROLL_TO, false ),
		];
	}

}
