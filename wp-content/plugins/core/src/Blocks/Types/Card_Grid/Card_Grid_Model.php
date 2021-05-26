<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Project\Blocks\Fields\CTA;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\card_grid\Card_Grid_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

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
		$cta  = $this->get( CTA::GROUP_CTA, [] );
		$link = wp_parse_args( $cta['link'] ?? [], [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT        => $link['title'],
			Link_Controller::URL            => $link['url'],
			Link_Controller::TARGET         => $link['target'],
			Link_Controller::ADD_ARIA_LABEL => $cta['add_aria_label'] ?? false,
			Link_Controller::ARIA_LABEL     => $cta['aria_label'] ?? '',
		];
	}
}
