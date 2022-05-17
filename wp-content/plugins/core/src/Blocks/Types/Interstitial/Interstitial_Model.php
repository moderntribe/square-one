<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Interstitial;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\interstitial\Interstitial_Block_Controller;

class Interstitial_Model extends Base_Model {

	public function get_data(): array {
		return [
			Interstitial_Block_Controller::ATTRS   => $this->get_attrs(),
			Interstitial_Block_Controller::CLASSES => $this->get_classes(),
			Interstitial_Block_Controller::LEADIN  => $this->get( Interstitial::LEADIN, '' ),
			Interstitial_Block_Controller::TITLE   => $this->get( Interstitial::TITLE, '' ),
			Interstitial_Block_Controller::CTA     => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Interstitial_Block_Controller::LAYOUT  => $this->get( Interstitial::LAYOUT, '' ),
			Interstitial_Block_Controller::MEDIA   => new Image( $this->get( Interstitial::IMAGE, [] ) ),
		];
	}

}
