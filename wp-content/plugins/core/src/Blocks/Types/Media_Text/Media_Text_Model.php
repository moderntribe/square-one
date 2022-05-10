<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\media_text\Media_Text_Block_Controller;

class Media_Text_Model extends Base_Model {

	public function get_data(): array {
		return [
			Media_Text_Block_Controller::ATTRS       => $this->get_attrs(),
			Media_Text_Block_Controller::CLASSES     => $this->get_classes(),
			Media_Text_Block_Controller::LAYOUT      => $this->get( Media_Text::LAYOUT, Media_Text::MEDIA_LEFT ),
			Media_Text_Block_Controller::WIDTH       => $this->get( Media_Text::WIDTH, Media_Text::WIDTH_GRID ),
			Media_Text_Block_Controller::TITLE       => $this->get( Media_Text::TITLE, '' ),
			Media_Text_Block_Controller::LEADIN      => $this->get( Media_Text::LEAD_IN, '' ),
			Media_Text_Block_Controller::DESCRIPTION => $this->get( Media_Text::DESCRIPTION, '' ),
			Media_Text_Block_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Media_Text_Block_Controller::MEDIA_TYPE  => $this->get( Media_Text::MEDIA_TYPE, '' ),
			Media_Text_Block_Controller::IMAGE       => new Image( $this->get( Media_Text::IMAGE, [] ) ),
			Media_Text_Block_Controller::VIDEO       => $this->get( Media_Text::EMBED, '' ),
		];
	}

}
