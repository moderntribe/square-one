<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Logos_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Logos_Block_Controller::TITLE       => $this->get( Logos::TITLE, '' ),
			Logos_Block_Controller::LEADIN      => $this->get( Logos::LEAD_IN, '' ),
			Logos_Block_Controller::DESCRIPTION => $this->get( Logos::DESCRIPTION, '' ),
			Logos_Block_Controller::CTA         => $this->get_cta_args(),
			Logos_Block_Controller::LOGOS       => $this->get( Logos::LOGOS, [] ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Logos::CTA, [] ), [
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
