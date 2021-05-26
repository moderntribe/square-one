<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Icon_Grid;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\icon_grid\Icon_Grid_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

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
			Icon_Grid_Controller::TITLE       => $this->get( Icon_Grid::TITLE, '' ),
			Icon_Grid_Controller::LEADIN      => $this->get( Icon_Grid::LEADIN, '' ),
			Icon_Grid_Controller::DESCRIPTION => $this->get( Icon_Grid::DESCRIPTION, '' ),
			Icon_Grid_Controller::CTA         => $this->get_cta_args(),
			Icon_Grid_Controller::ICONS       => $this->get( Icon_Grid::ICONS, [] ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Icon_Grid::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta['title'],
			Link_Controller::URL     => $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
		];
	}

}
