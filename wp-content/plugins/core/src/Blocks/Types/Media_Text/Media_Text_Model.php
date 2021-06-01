<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\media_text\Media_Text_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Media_Text_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Media_Text_Block_Controller::ATTRS       => $this->get_attrs(),
			Media_Text_Block_Controller::CLASSES     => $this->get_classes(),
			Media_Text_Block_Controller::LAYOUT      => $this->get( Media_Text::LAYOUT, Media_Text::MEDIA_LEFT ),
			Media_Text_Block_Controller::WIDTH       => $this->get( Media_Text::WIDTH, Media_Text::WIDTH_GRID ),
			Media_Text_Block_Controller::TITLE       => $this->get( Media_Text::TITLE, '' ),
			Media_Text_Block_Controller::LEADIN      => $this->get( Media_Text::LEAD_IN, '' ),
			Media_Text_Block_Controller::DESCRIPTION => $this->get( Media_Text::DESCRIPTION, '' ),
			Media_Text_Block_Controller::CTA         => $this->get_cta_args(),
			Media_Text_Block_Controller::MEDIA_TYPE  => $this->get( Media_Text::MEDIA_TYPE, '' ),
			Media_Text_Block_Controller::IMAGE       => $this->get( Media_Text::IMAGE, 0 ),
			Media_Text_Block_Controller::VIDEO       => $this->get( Media_Text::EMBED, '' ),
		];
	}

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
