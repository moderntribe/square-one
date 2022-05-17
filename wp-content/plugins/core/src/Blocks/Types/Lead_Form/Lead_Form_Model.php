<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\lead_form\Lead_Form_Block_Controller;

class Lead_Form_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Lead_Form_Block_Controller::ATTRS       => $this->get_attrs(),
			Lead_Form_Block_Controller::CLASSES     => $this->get_classes(),
			Lead_Form_Block_Controller::LAYOUT      => $this->get( Lead_Form::LAYOUT, Lead_Form::LAYOUT_BOTTOM ),
			Lead_Form_Block_Controller::WIDTH       => $this->get( Lead_Form::WIDTH, Lead_Form::WIDTH_GRID ),
			Lead_Form_Block_Controller::TITLE       => $this->get( Lead_Form::TITLE, '' ),
			Lead_Form_Block_Controller::LEADIN      => $this->get( Lead_Form::LEAD_IN, '' ),
			Lead_Form_Block_Controller::DESCRIPTION => $this->get( Lead_Form::DESCRIPTION, '' ),
			Lead_Form_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Lead_Form_Block_Controller::BACKGROUND  => $this->get( Lead_Form::BACKGROUND, Lead_Form::BACKGROUND_LIGHT ),
			Lead_Form_Block_Controller::FORM_FIELDS => $this->get( Lead_Form::FORM_FIELDS, Lead_Form::FORM_STACKED ),

		];
	}

}
