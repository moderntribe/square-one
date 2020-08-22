<?php


namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\lead_form\Lead_Form_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Lead_Form_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Lead_Form_Block_Controller::TITLE       => $this->get( Lead_Form::TITLE, '' ),
			Lead_Form_Block_Controller::LEADIN      => $this->get( Lead_Form::LEAD_IN, '' ),
			Lead_Form_Block_Controller::DESCRIPTION => $this->get( Lead_Form::DESCRIPTION, '' ),
			Lead_Form_Block_Controller::CTA         => $this->get_cta_args(),
			Lead_Form_Block_Controller::FORM   => (int) $this->get( Lead_Form::FORM, 0 ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Lead_Form::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta[ 'title' ],
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
		];
	}
}
