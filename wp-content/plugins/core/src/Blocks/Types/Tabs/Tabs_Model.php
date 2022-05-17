<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Tab_Collection;

class Tabs_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Tabs_Block_Controller::ATTRS       => $this->get_attrs(),
			Tabs_Block_Controller::CLASSES     => $this->get_classes(),
			Tabs_Block_Controller::LAYOUT      => $this->get( Tabs::LAYOUT, Tabs::LAYOUT_HORIZONTAL ),
			Tabs_Block_Controller::TITLE       => $this->get( Tabs::TITLE, '' ),
			Tabs_Block_Controller::LEADIN      => $this->get( Tabs::LEAD_IN, '' ),
			Tabs_Block_Controller::DESCRIPTION => $this->get( Tabs::DESCRIPTION, '' ),
			Tabs_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Tabs_Block_Controller::TABS        => Tab_Collection::create( $this->get( Tabs::TABS, [] ) ),
		];
	}

}
