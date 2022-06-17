<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Icon_Grid;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\icon_grid\Icon_Grid_Controller;
use Tribe\Project\Templates\Models\Collections\Icon_Collection;

/**
 * Class Icon_Grid_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component
 */
class Icon_Grid_Model extends Base_Model {

	public function get_data(): array {
		return [
			Icon_Grid_Controller::ATTRS       => $this->get_attrs(),
			Icon_Grid_Controller::CLASSES     => $this->get_classes(),
			Icon_Grid_Controller::LAYOUT      => $this->get( Icon_Grid::LAYOUT, '' ),
			Icon_Grid_Controller::TITLE       => $this->get( Icon_Grid::TITLE, '' ),
			Icon_Grid_Controller::LEADIN      => $this->get( Icon_Grid::LEADIN, '' ),
			Icon_Grid_Controller::DESCRIPTION => $this->get( Icon_Grid::DESCRIPTION, '' ),
			Icon_Grid_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Icon_Grid_Controller::ICONS       => Icon_Collection::create( $this->get( Icon_Grid::ICONS, [] ) ),
		];
	}

}
