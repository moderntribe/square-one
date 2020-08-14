<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Models\Accordion_Row;

class Model extends Base_Model {
	public function get_data() {
		return [
			'layout'      => $this->get(
				Accordion::LAYOUT,
				Accordion::LAYOUT_STACKED
			),
			'rows'        => $this->get_accordion_rows(),
			'header'      => $this->get( Accordion::TITLE ),
			'description' => $this->get( Accordion::DESCRIPTION ),
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