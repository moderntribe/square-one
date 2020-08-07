<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Project\Blocks\Types\Base_Model;

class Model extends Base_Model {
	const LAYOUT  = 'layout';
	const MEDIA   = 'media';
	const CONTENT = 'content';
	const LEAD_IN = 'leadin';
	const TITLE   = 'title';
	const TEXT    = 'text';
	const CTA     = 'cta';

	public function get_data(): array {
		return [
			self::LAYOUT  => $this->get_layout(),
			self::MEDIA   => $this->get_media(),
			self::CONTENT => $this->get_content(),
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
			self::LEAD_IN => $this->get( Hero::LEAD_IN, '' ),
			self::TITLE   => $this->get( Hero::TITLE, '' ),
			self::TEXT    => $this->get( Hero::DESCRIPTION, '' ),
			self::CTA     => wp_parse_args( $this->get( Hero::CTA, [] ), [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] ),
		];
	}

}
