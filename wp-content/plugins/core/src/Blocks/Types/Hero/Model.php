<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;

class Model extends Base_Model {

	public function get_data(): array {
		return [
			'layout'      => $this->get( Hero::LAYOUT, Hero::LAYOUT_LEFT ),
			'media'       => $this->get( Hero::IMAGE, null ),
			'leadin'      => $this->get( Hero::LEAD_IN, '' ),
			'title'       => $this->get( Hero::TITLE, '' ),
			'description' => $this->get( Hero::DESCRIPTION, '' ),
			'cta'         => $this->get_cta_args(),
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
			'content' => $cta[ 'title' ],
			'url'     => $cta[ 'url' ],
			'target'  => $cta[ 'target' ],
		];
	}
}
