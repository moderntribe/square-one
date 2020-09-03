<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\hero\Hero_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Hero_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Hero_Block_Controller::CLASSES     => $this->getClassName(),
			Hero_Block_Controller::LAYOUT      => $this->get( Hero::LAYOUT, Hero::LAYOUT_LEFT ),
			Hero_Block_Controller::MEDIA       => $this->get( Hero::IMAGE, 0 ),
			Hero_Block_Controller::TITLE       => $this->get( Hero::TITLE, '' ),
			Hero_Block_Controller::LEADIN      => $this->get( Hero::LEAD_IN, '' ),
			Hero_Block_Controller::DESCRIPTION => $this->get( Hero::DESCRIPTION, '' ),
			Hero_Block_Controller::CTA         => $this->get_cta_args(),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Hero::CTA, [] ), [
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
