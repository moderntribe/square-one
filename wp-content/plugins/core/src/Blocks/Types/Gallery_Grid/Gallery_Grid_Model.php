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
			Gallery_Grid_Controller::ATTRS          => $this->get_attrs(),
			Gallery_Grid_Controller::CLASSES        => $this->get_classes(),
			Gallery_Grid_Controller::LEAD_IN        => $this->get( Gallery_Grid::LEAD_IN, '' ),
			Gallery_Grid_Controller::TITLE          => $this->get( Gallery_Grid::TITLE, '' ),
			Gallery_Grid_Controller::DESCRIPTION    => $this->get( Gallery_Grid::DESCRIPTION, '' ),
			Gallery_Grid_Controller::GALLERY_IMAGES => $this->get( Gallery_Grid::GALLERY_IMAGES, [] ),
			Gallery_Grid_Controller::COLUMNS        => $this->get( Gallery_Grid::COLUMNS, Gallery_Grid::COLUMNS_THREE ),
			Gallery_Grid_Controller::USE_SLIDESHOW  => $this->get( Gallery_Grid::USE_SLIDESHOW, false ),
		];
	}

}
