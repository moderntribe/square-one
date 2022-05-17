<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\quote\Quote_Block_Controller;
use Tribe\Project\Templates\Models\Quote as QuoteFieldModel;

class Quote_Model extends Base_Model {

	public function get_data(): array {
		return [
			Quote_Block_Controller::ATTRS   => $this->get_attrs(),
			Quote_Block_Controller::CLASSES => $this->get_classes(),
			Quote_Block_Controller::QUOTE   => new QuoteFieldModel( $this->get( Quote::QUOTE_GROUP, [] ) ),
			Quote_Block_Controller::MEDIA   => new Image( $this->get( Quote::IMAGE, [] ) ),
			Quote_Block_Controller::LAYOUT  => $this->get( Quote::LAYOUT, Quote::MEDIA_OVERLAY ),
		];
	}

}
