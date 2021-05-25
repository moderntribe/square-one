<?php


namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Project\Blocks\Fields\CTA;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\links\Links_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Links_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Links_Block_Controller::ATTRS       => $this->get_attrs(),
			Links_Block_Controller::CLASSES     => $this->get_classes(),
			Links_Block_Controller::TITLE       => $this->get( Links::TITLE, '' ),
			Links_Block_Controller::LEADIN      => $this->get( Links::LEAD_IN, '' ),
			Links_Block_Controller::DESCRIPTION => $this->get( Links::DESCRIPTION, '' ),
			Links_Block_Controller::CTA         => $this->get_cta_args(),
			Links_Block_Controller::LINKS       => $this->get( Links::LINKS, [] ),
			Links_Block_Controller::LINKS_TITLE => $this->get( Links::LINKS_TITLE, '' ),
			Links_Block_Controller::LAYOUT      => $this->get( Links::LAYOUT, Links::LAYOUT_STACKED ),
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
