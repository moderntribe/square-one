<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Gallery_Grid;

use Tribe\Project\Templates\Components\blocks\gallery_grid\Gallery_Grid_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

/**
 * Class Gallery_Grid_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component
 */
class Gallery_Grid_Model extends \Tribe\Project\Blocks\Types\Base_Model {
	public function get_data(): array {
		return [
			Gallery_Grid_Controller::CLASSES     => $this->get_classes(),
			Gallery_Grid_Controller::TITLE       => $this->get( Gallery_Grid::TITLE, '' ),
			Gallery_Grid_Controller::DESCRIPTION => $this->get( Gallery_Grid::DESCRIPTION, '' ),
			Gallery_Grid_Controller::CTA         => $this->get_cta_args(),
			Gallery_Grid_Controller::GALLERY     => $this->get( Gallery_Grid::GALLERY, '' ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Gallery_Grid::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta[ 'title' ],
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
		];
	}

}
