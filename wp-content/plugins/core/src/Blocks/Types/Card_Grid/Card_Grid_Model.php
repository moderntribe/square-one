<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\card_grid\Card_Grid_Controller;

/**
 * @see \Tribe\Project\Blocks\Middleware\Post_Loop\Model_Middleware\Post_Loop_Field_Model_Middleware
 */
class Card_Grid_Model extends Base_Model {

	public function init_data(): array {
		return [
			Card_Grid_Controller::ATTRS       => $this->get_attrs(),
			Card_Grid_Controller::CLASSES     => $this->get_classes(),
			Card_Grid_Controller::TITLE       => $this->get( Card_Grid::TITLE, '' ),
			Card_Grid_Controller::LEADIN      => $this->get( Card_Grid::LEADIN, '' ),
			Card_Grid_Controller::DESCRIPTION => $this->get( Card_Grid::DESCRIPTION, '' ),
			Card_Grid_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Card_Grid_Controller::POSTS       => $this->get( Card_Grid::POST_LIST, [] ),
			Card_Grid_Controller::LAYOUT      => $this->get( Card_Grid::LAYOUT, Card_Grid::LAYOUT_STACKED ),
		];
	}

}
