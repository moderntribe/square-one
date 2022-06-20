<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Slider;

use Tribe\Libs\Field_Models\Models\Link;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\gallery_slider\Gallery_Slider_Controller;

/**
 * Class Gallery_Slider_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component
 */
class Gallery_Slider_Model extends Base_Model {

	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Gallery_Slider_Controller::ATTRS           => $this->get_attrs(),
			Gallery_Slider_Controller::CLASSES         => $this->get_classes(),
			Gallery_Slider_Controller::TITLE           => $this->get( Gallery_Slider::TITLE, '' ),
			Gallery_Slider_Controller::DESCRIPTION     => $this->get( Gallery_Slider::DESCRIPTION, '' ),
			Gallery_Slider_Controller::CTA             => new Link( $this->get( Gallery_Slider::CTA, [] ) ),
			Gallery_Slider_Controller::GALLERY         => $this->get( Gallery_Slider::GALLERY, [] ),
			Gallery_Slider_Controller::IMAGE_RATIO     => $this->get( Gallery_Slider::IMAGE_RATIO, Gallery_Slider::FIXED ),
			Gallery_Slider_Controller::CAPTION_DISPLAY => $this->get( Gallery_Slider::CAPTION_DISPLAY, Gallery_Slider::CAPTION_DISPLAY_SHOW ),
		];
	}

}
