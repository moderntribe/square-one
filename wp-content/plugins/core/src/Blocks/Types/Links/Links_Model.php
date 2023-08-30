<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\links\Links_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Link_Collection;

class Links_Model extends Base_Model {

	public function init_data(): array {
		return [
			Links_Block_Controller::ATTRS       => $this->get_attrs(),
			Links_Block_Controller::CLASSES     => $this->get_classes(),
			Links_Block_Controller::TITLE       => $this->get( Links::TITLE, '' ),
			Links_Block_Controller::LEADIN      => $this->get( Links::LEAD_IN, '' ),
			Links_Block_Controller::DESCRIPTION => $this->get( Links::DESCRIPTION, '' ),
			Links_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Links_Block_Controller::LINKS       => Link_Collection::create( $this->get( Links::LINKS, [] ) ),
			Links_Block_Controller::LINKS_TITLE => $this->get( Links::LINKS_TITLE, '' ),
			Links_Block_Controller::LAYOUT      => $this->get( Links::LAYOUT, Links::LAYOUT_STACKED ),
		];
	}

}
