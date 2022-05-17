<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\stats\Stats_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Statistic_Collection;

class Stats_Model extends Base_Model {

	public function get_data(): array {
		return [
			Stats_Block_Controller::ATTRS         => $this->get_attrs(),
			Stats_Block_Controller::CLASSES       => $this->get_classes(),
			Stats_Block_Controller::LAYOUT        => $this->get( Stats::LAYOUT, Stats::LAYOUT_STACKED ),
			Stats_Block_Controller::CONTENT_ALIGN => $this->get( Stats::CONTENT_ALIGN, Stats::CONTENT_ALIGN_CENTER ),
			Stats_Block_Controller::DIVIDERS      => $this->get( Stats::DIVIDERS, Stats::DIVIDERS_SHOW ),
			Stats_Block_Controller::TITLE         => $this->get( Stats::TITLE, '' ),
			Stats_Block_Controller::LEADIN        => $this->get( Stats::LEAD_IN, '' ),
			Stats_Block_Controller::DESCRIPTION   => $this->get( Stats::DESCRIPTION, '' ),
			Stats_Block_Controller::CTA           => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Stats_Block_Controller::STATS         => Statistic_Collection::create( $this->get( Stats::STATS, [] ) ),
		];
	}

}
