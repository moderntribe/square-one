<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\accordion\Accordion_Block_Controller;
use Tribe\Project\Templates\Models\Accordion_Row;

class Accordion_Model extends Base_Model {
	public function get_data() {
		return [
			Accordion_Block_Controller::LAYOUT      => $this->get(
				Accordion::LAYOUT,
				Accordion::LAYOUT_STACKED
			),
			Accordion_Block_Controller::ROWS        => $this->get_accordion_rows(),
			Accordion_Block_Controller::HEADER      => $this->get( Accordion::TITLE, '' ),
			Accordion_Block_Controller::DESCRIPTION => $this->get(
				Accordion::DESCRIPTION,
				''
			),
		];
	}

	/**
	 * @return array
	 */
	protected function get_accordion_rows(): array {
		$rows = $this->get( Accordion::ACCORDION, [] );
		$data = [];
		foreach ( $rows as $row ) {
			$data[] = new Accordion_Row(
				$row[ Accordion::ROW_HEADER ],
				$row[ Accordion::ROW_CONTENT ],
				uniqid( 'accordion-header-' ),
				uniqid( 'accordion-content-' ),
			);
		}

		return $data;
	}
}
