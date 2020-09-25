<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Content_Columns;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\content_columns\Content_Columns_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Content_Column;

class Content_Columns_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Content_Columns_Block_Controller::TITLE            => $this->get( Content_Columns::TITLE, '' ),
			Content_Columns_Block_Controller::LEADIN           => $this->get( Content_Columns::LEADIN, '' ),
			Content_Columns_Block_Controller::DESCRIPTION      => $this->get( Content_Columns::DESCRIPTION, '' ),
			Content_Columns_Block_Controller::CTA              => $this->get_cta_args( $this->get( Content_Columns::CTA, [] ) ),
			Content_Columns_Block_Controller::CLASSES          => $this->get_classes(),
			Content_Columns_Block_Controller::COLUMNS          => $this->get_rows(),
			Content_Columns_Block_Controller::CONTENT_ALIGN    => $this->get( Content_Columns::CONTENT_ALIGN, Content_Columns::CONTENT_ALIGN_LEFT ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args( array $cta ): array {
		$cta = wp_parse_args( $cta, [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => esc_html( $cta[ 'title' ] ),
			Link_Controller::URL     => esc_url( $cta[ 'url' ] ),
			Link_Controller::TARGET  => esc_attr( $cta[ 'target' ] ),
		];
	}

	/**
	 * @return Content_Column[]
	 */
	public function get_rows(): array {
		$rows = $this->get( Content_Columns::COLUMNS, [] );
		$data = [];
		foreach ( $rows as $row ) {
			$data[] = new Content_Column(
				$row[ Content_Columns::COLUMN_TITLE ],
				$row[ Content_Columns::COLUMN_CONTENT ],
				$this->get_cta_args( (array) $row[ Content_Columns::COLUMN_CTA ] ),
			);
		}

		return $data;
	}
}
