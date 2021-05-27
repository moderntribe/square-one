<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Tab as Tab_Model;

class Tabs_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Tabs_Block_Controller::ATTRS       => $this->get_attrs(),
			Tabs_Block_Controller::CLASSES     => $this->get_classes(),
			Tabs_Block_Controller::LAYOUT      => $this->get( Tabs::LAYOUT, Tabs::LAYOUT_HORIZONTAL ),
			Tabs_Block_Controller::TITLE       => $this->get( Tabs::TITLE, '' ),
			Tabs_Block_Controller::LEADIN      => $this->get( Tabs::LEAD_IN, '' ),
			Tabs_Block_Controller::DESCRIPTION => $this->get( Tabs::DESCRIPTION, '' ),
			Tabs_Block_Controller::CTA         => $this->get_cta_args(),
			Tabs_Block_Controller::TABS        => $this->get_tabs(),
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

	/**
	 * @return array
	 */
	private function get_tabs(): array {
		$tab_objects = [];
		$tabs_data   = $this->get( Tabs::TABS, [] );

		if ( empty( $tabs_data ) ) {
			return $tab_objects;
		}

		foreach ( $tabs_data as $tab ) {
			$tab_objects[] = new Tab_Model( $tab[ Tabs::TAB_LABEL ], $tab[ Tabs::TAB_CONTENT ] );
		}

		return $tab_objects;
	}

}
