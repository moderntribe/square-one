<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Models\Image;
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
			Logos_Block_Controller::LOGOS   => $this->get( Logos::LOGOS, null ),
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

	/**
	 * @return array
	 */
	private function get_logos(): array {
		$logos = $this->get( Logos::LOGOS, [] );
		$data = [];
		foreach ( $logos as $logo ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $logo[ Logos::LOGO_IMAGE ] ) ) {
				continue;
			}
			$link = wp_parse_args( $logo[ Logos::LOGO_LINK ], [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] );
			$data[] = [
				'attachment' => Image::factory( (int) $logo[ Logos::LOGO_IMAGE ]['id'] ),
				'link' => [
					'content' => $link['title'],
					'url'     => $link['url'],
					'target'  => $link['target'],
				],
			];
		}

		return $data;
	}
}
