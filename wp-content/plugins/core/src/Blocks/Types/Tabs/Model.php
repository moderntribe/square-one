<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;
use Tribe\Project\Templates\Models\Tab as Tab_Model;

/**
 * Class Model
 *
 * @package Tribe\Project\Blocks\Types\Tabs
 */
class Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Tabs_Block::LAYOUT      => $this->get_layout(),
			Tabs_Block::TITLE       => $this->get_title(),
			Tabs_Block::DESCRIPTION => $this->get_description(),
			Tabs_Block::TABS        => $this->get_tabs(),
		];
	}

	/**
	 * @return string
	 */
	private function get_layout(): string {
		return $this->get( Tabs_Block::LAYOUT, Tabs_Block::LAYOUT_HORIZONTAL );
	}

	/**
	 * @return string
	 */
	private function get_title(): string {
		return $this->get( Tabs_Block::TITLE );
	}

	/**
	 * @return string
	 */
	private function get_description(): string {
		return $this->get( Tabs_Block::DESCRIPTION );
	}

	/**
	 * @return array
	 */
	private function get_tabs(): array {
		$tab_objects = [];
		$tabs_data   = $this->get( Tabs_Block::TABS );

		foreach ( $tabs_data as $tab ) {
			$tab_objects[] = new Tab_Model( $tab[ Tabs_Block::TAB_LABEL ], $tab[ Tabs_Block::TAB_CONTENT ] );
		}

		return $tab_objects;
	}
}
