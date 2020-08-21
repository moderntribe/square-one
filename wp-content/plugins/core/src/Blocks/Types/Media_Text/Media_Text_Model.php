<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\media_text\Media_Text_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Media_Text_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Media_Text_Block_Controller::LAYOUT     => $this->get( Media_Text::LAYOUT, Media_Text::MEDIA_LEFT ),
			Media_Text_Block_Controller::WIDTH      => $this->get( Media_Text::WIDTH, Media_Text::WIDTH_GRID ),
			Media_Text_Block_Controller::TITLE      => $this->get( Media_Text::TITLE, '' ),
			Media_Text_Block_Controller::CONTENT    => $this->get( Media_Text::CONTENT, '' ),
			Media_Text_Block_Controller::CTA        => $this->get_cta_args(),
			Media_Text_Block_Controller::MEDIA_TYPE => $this->get( Media_Text::MEDIA_TYPE, '' ),
			Media_Text_Block_Controller::IMAGE      => $this->get( Media_Text::IMAGE, 0 ),
			Media_Text_Block_Controller::VIDEO      => $this->get( Media_Text::EMBED, '' ),
		];
	}

	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Media_Text::CTA, [] ), [
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
}
