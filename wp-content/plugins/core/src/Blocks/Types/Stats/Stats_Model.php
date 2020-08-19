<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\stats\Stats_Block_Controller;

class Stats_Model extends Base_Model {

	public function get_data(): array {
		return [
			Stats_Block_Controller::LAYOUT           => $this->get( Stats::LAYOUT, Stats::LAYOUT_STACKED ),
			Stats_Block_Controller::CONTENT_ALIGN    => $this->get( Stats::CONTENT_ALIGN, Stats::CONTENT_ALIGN_LEFT ),
			Stats_Block_Controller::DISPLAY_DIVIDERS => $this->get( Stats::DISPLAY_DIVIDERS, true ),
			Stats_Block_Controller::TITLE            => $this->get( Stats::TITLE, '' ),
			Stats_Block_Controller::DESCRIPTION      => $this->get( Stats::DESCRIPTION, '' ),
			Stats_Block_Controller::STATS            => $this->get( Stats::STATS, [] ),
		];
	}
}
