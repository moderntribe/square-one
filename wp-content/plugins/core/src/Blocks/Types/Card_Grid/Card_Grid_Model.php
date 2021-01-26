<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Blocks\Types\Post_List\Post_List;
use Tribe\Project\Templates\Components\blocks\card_grid\Card_Grid_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Card_Grid_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Card_Grid_Controller::ATTRS       => $this->get_attrs(),
			Card_Grid_Controller::CLASSES     => $this->get_classes(),
			Card_Grid_Controller::TITLE       => $this->get( Card_Grid::TITLE, '' ),
			Card_Grid_Controller::DESCRIPTION => $this->get( Card_Grid::DESCRIPTION, '' ),
			Card_Grid_Controller::CTA         => $this->get_cta_args(),
			Card_Grid_Controller::POSTS       => $this->get( Card_Grid::POST_LIST, [] ),
			Card_Grid_Controller::LAYOUT      => $this->get( Card_Grid::LAYOUT, Card_Grid::LAYOUT_STACKED ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Card_Grid::CTA, [] ), [
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
