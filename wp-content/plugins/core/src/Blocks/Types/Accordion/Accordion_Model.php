<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Project\Blocks\Fields\CTA;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\accordion\Accordion_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Accordion_Row;

class Accordion_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Accordion_Block_Controller::ATTRS       => $this->get_attrs(),
			Accordion_Block_Controller::CLASSES     => $this->get_classes(),
			Accordion_Block_Controller::LAYOUT      => $this->get( Accordion::LAYOUT, Accordion::LAYOUT_STACKED ),
			Accordion_Block_Controller::ROWS        => $this->get_accordion_rows(),
			Accordion_Block_Controller::TITLE       => $this->get( Accordion::TITLE, '' ),
			Accordion_Block_Controller::LEADIN      => $this->get( Accordion::LEAD_IN, '' ),
			Accordion_Block_Controller::DESCRIPTION => $this->get( Accordion::DESCRIPTION, '' ),
			Accordion_Block_Controller::CTA         => $this->get_cta_args(),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta  = $this->get( CTA::GROUP_CTA );
		$link = wp_parse_args( $cta['link'], [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT        => $link['title'],
			Link_Controller::URL            => $link['url'],
			Link_Controller::TARGET         => $link['target'],
			Link_Controller::ADD_ARIA_LABEL => $cta['add_aria_label'],
			Link_Controller::ARIA_LABEL     => $cta['aria_label'],
		];
	}

	/**
	 * @return array
	 */
	public function get_accordion_rows(): array {
		$rows = $this->get( Accordion::ACCORDION, [] );
		$data = [];
		foreach ( $rows as $row ) {
			$data[] = new Accordion_Row(
				$row[ Accordion::ROW_HEADER ],
				$row[ Accordion::ROW_CONTENT ]
			);
		}

		return $data;
	}
}
