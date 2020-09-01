<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;
use Tribe\Project\Templates\Models\Tab as Tab_Model;

class Tabs_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
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
		$cta = wp_parse_args( $this->get( Tabs::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta[ 'title' ],
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
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
