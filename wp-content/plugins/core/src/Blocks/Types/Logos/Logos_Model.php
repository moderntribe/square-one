<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller;

class Logos_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data() {
		return [
			Logos_Block_Controller::TITLE   => $this->get( Logos::TITLE, '' ),
			Logos_Block_Controller::CONTENT => $this->get( Logos::DESCRIPTION, '' ),
			Logos_Block_Controller::CTA     => $this->get_cta_args(),
			Logos_Block_Controller::LOGOS   => $this->get( Logos::LOGOS, [] ),
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
			'content' => $cta[ 'title' ],
			'url'     => $cta[ 'url' ],
			'target'  => $cta[ 'target' ],
		];
	}
}
