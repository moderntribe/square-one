<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;

class Model extends Base_Model {

	public function get_data(): array {
		return [
			'layout'  => $this->get_layout(),
			'media'   => $this->get_media_id(),
			'content' => $this->get_content_args(),
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
	private function get_media_id() {
		return $this->get( Hero::IMAGE, false );
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

	/**
	 * @return array
	 */
	private function get_content_args(): array {
		return [
			Hero::LEAD_IN     => $this->get( Hero::LEAD_IN, '' ),
			Hero::TITLE       => $this->get( Hero::TITLE, '' ),
			Hero::DESCRIPTION => $this->get( Hero::DESCRIPTION, '' ),
			Hero::CTA         => $this->get_cta_args(),
		];
	}

}
