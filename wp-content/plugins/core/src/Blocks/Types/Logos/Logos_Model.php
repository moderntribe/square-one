<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Fields\CTA;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Logos_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Logos_Block_Controller::ATTRS       => $this->get_attrs(),
			Logos_Block_Controller::CLASSES     => $this->get_classes(),
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
		$cta  = $this->get( CTA::GROUP_CTA, [] );
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
