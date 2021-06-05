<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Columns;

use Tribe\Project\Blocks\Fields\Cta_Field;
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
			Content_Columns_Block_Controller::ATTRS         => $this->get_attrs(),
			Content_Columns_Block_Controller::CLASSES       => $this->get_classes(),
			Content_Columns_Block_Controller::TITLE         => $this->get( Content_Columns::TITLE, '' ),
			Content_Columns_Block_Controller::LEADIN        => $this->get( Content_Columns::LEADIN, '' ),
			Content_Columns_Block_Controller::DESCRIPTION   => $this->get( Content_Columns::DESCRIPTION, '' ),
			Content_Columns_Block_Controller::CTA           => $this->get_cta_args( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Content_Columns_Block_Controller::COLUMNS       => $this->get_rows(),
			Content_Columns_Block_Controller::CONTENT_ALIGN => $this->get( Content_Columns::CONTENT_ALIGN, Content_Columns::CONTENT_ALIGN_LEFT ),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args( array $cta ): array {
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
	 * @return \Tribe\Project\Templates\Models\Content_Column[]
	 */
	public function get_rows(): array {
		$rows = $this->get( Content_Columns::COLUMNS, [] );
		$data = [];
		foreach ( $rows as $row ) {
			$data[] = new Content_Column(
				$row[ Content_Columns::COLUMN_TITLE ],
				$row[ Content_Columns::COLUMN_CONTENT ],
				$this->get_cta_args( (array) $row[ Cta_Field::GROUP_CTA ] ),
			);
		}

		return $data;
	}

}
