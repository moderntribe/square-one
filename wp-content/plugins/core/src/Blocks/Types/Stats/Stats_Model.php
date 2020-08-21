<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\stats\Stats_Block_Controller;
use Tribe\Project\Templates\Models\Statistic as Statistic_Model;

class Stats_Model extends Base_Model {

	public function get_data(): array {
		return [
			Stats_Block_Controller::LAYOUT           => $this->get( Stats::LAYOUT, Stats::LAYOUT_STACKED ),
			Stats_Block_Controller::CONTENT_ALIGN    => $this->get( Stats::CONTENT_ALIGN, Stats::CONTENT_ALIGN_LEFT ),
			Stats_Block_Controller::DISPLAY_DIVIDERS => $this->get( Stats::DISPLAY_DIVIDERS, true ),
			Stats_Block_Controller::TITLE            => $this->get( Stats::TITLE, '' ),
			Stats_Block_Controller::DESCRIPTION      => $this->get( Stats::DESCRIPTION, '' ),
			Stats_Block_Controller::STATS            => $this->get_stats(),
		];
	}

	/**
	 * @return array
	 */
	private function get_stats(): array {
		$stat_objects = [];
		$stats_data   = get_field( Stats::STATS );

		if ( empty( $stats_data ) ) {
			return $stat_objects;
		}

		foreach ( $stats_data as $item ) {
			$stat_objects[] = new Statistic_Model( $item[ Stats::ROW_VALUE ], $item[ Stats::ROW_LABEL ] );
		}

		return $stat_objects;
	}
}
