<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\lead_form\Lead_Form_Controller;

class Lead_Form_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Lead_Form_Controller::LAYOUT      => $this->get( Lead_Form::LAYOUT, Lead_Form::LAYOUT_CENTER ),
			Lead_Form_Controller::WIDTH       => $this->get( Lead_Form::WIDTH, Lead_Form::WIDTH_GRID ),
			Lead_Form_Controller::DESCRIPTION => $this->get( Lead_Form::DESCRIPTION, '' ),
			Lead_Form_Controller::TITLE       => $this->get( Lead_Form::TITLE, '' ),
			Lead_Form_Controller::FORM        => (int) $this->get( Lead_Form::FORM, 0 ),
		];
	}
}
