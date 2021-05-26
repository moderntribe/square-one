<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Grid;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\gallery_grid\Gallery_Grid_Controller;

/**
 * Class Gallery_Grid_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component
 */
class Gallery_Grid_Model extends Base_Model {

	public function get_data(): array {
		return [
			Gallery_Grid_Controller::ATTRS       => $this->get_attrs(),
			Gallery_Grid_Controller::CLASSES     => $this->get_classes(),
			Gallery_Grid_Controller::TITLE       => $this->get( Gallery_Grid::TITLE, '' ),
			Gallery_Grid_Controller::DESCRIPTION => $this->get( Gallery_Grid::DESCRIPTION, '' ),
			Gallery_Grid_Controller::GALLERY     => $this->get( Gallery_Grid::GALLERY, [] ),
			Gallery_Grid_Controller::GRID_LAYOUT => $this->get( Gallery_Grid::GRID_LAYOUT, Gallery_Grid::THREE ),
			Gallery_Grid_Controller::SLIDESHOW   => $this->get( Gallery_Grid::SLIDESHOW, false ),
		];
	}

}
