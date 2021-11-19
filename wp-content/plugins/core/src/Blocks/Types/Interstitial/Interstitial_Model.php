<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Interstitial;

use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\interstitial\Interstitial_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Interstitial_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Interstitial_Block_Controller::ATTRS   => $this->get_attrs(),
			Interstitial_Block_Controller::CLASSES => $this->get_classes(),
			Interstitial_Block_Controller::LEADIN  => $this->get( Interstitial::LEADIN, '' ),
			Interstitial_Block_Controller::TITLE   => $this->get( Interstitial::TITLE, '' ),
			Interstitial_Block_Controller::CTA     => $this->get_cta_args(),
			Interstitial_Block_Controller::LAYOUT  => $this->get( Interstitial::LAYOUT, '' ),
			Interstitial_Block_Controller::MEDIA   => $this->get( Interstitial::IMAGE, 0 ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta  = $this->get( Cta_Field::GROUP_CTA, [] );
		$link = wp_parse_args( $cta['link'] ?? [], [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT        => $link['title'],
			Link_Controller::URL            => $link['url'],
			Link_Controller::TARGET         => $link['target'],
			Link_Controller::ADD_ARIA_LABEL => $cta['add_aria_label'] ?? false,
			Link_Controller::ARIA_LABEL     => $cta['aria_label'] ?? '',
		];
	}

}
