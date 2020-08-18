<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\quote\Quote_Block_Controller;

class Quote_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Quote_Block_Controller::CITE_TITLE => $this->get( Quote::CITE_TITLE, '' ),
			Quote_Block_Controller::CITE_NAME  => $this->get( Quote::CITE_NAME, '' ),
			Quote_Block_Controller::CITE_IMAGE => $this->get( Quote::CITE_IMAGE, null ),
			Quote_Block_Controller::QUOTE_TEXT => $this->get( Quote::QUOTE, '' ),
			Quote_Block_Controller::MEDIA      => $this->get( Quote::IMAGE, null ),
			Quote_Block_Controller::LAYOUT     => $this->get(
				Quote::LAYOUT,
				Quote::MEDIA_OVERLAY
			),

		];
	}
}
