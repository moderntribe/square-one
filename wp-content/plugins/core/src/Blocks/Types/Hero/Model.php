<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;

class Model extends Base_Model {

	public function get_data(): array {
		return [
			'layout'  => $this->get_layout(),
			'media'   => $this->get_media(),
			'content' => $this->get_content(),
		];
	}

	/**
	 * @return bool|mixed
	 */
	private function get_layout() {
		return $this->get( Hero::LAYOUT, Hero::LAYOUT_LEFT );
	}

	/**
	 * @return int|bool
	 */
	private function get_media() {
		return $this->get( Hero::IMAGE, false );
	}

	/**
	 * @return array
	 */
	private function get_content(): array {
		return [
			Hero::LEAD_IN     => $this->get( Hero::LEAD_IN, '' ),
			Hero::TITLE       => $this->get( Hero::TITLE, '' ),
			Hero::DESCRIPTION => $this->get( Hero::DESCRIPTION, '' ),
			Hero::CTA         => wp_parse_args( $this->get( Hero::CTA, [] ), [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] ),
		];
	}

}
