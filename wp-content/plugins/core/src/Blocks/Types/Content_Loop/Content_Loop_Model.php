<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;

/**
 * Class Content_Loop_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component
 */
class Content_Loop_Model extends Base_Model {

	public function get_data(): array {
		return [
			Content_Loop_Controller::ATTRS       => $this->get_attrs(),
			Content_Loop_Controller::CLASSES     => $this->get_classes(),
			Content_Loop_Controller::TITLE       => $this->get( Content_Loop::TITLE, '' ),
			Content_Loop_Controller::LEADIN      => $this->get( Content_Loop::LEADIN, '' ),
			Content_Loop_Controller::DESCRIPTION => $this->get( Content_Loop::DESCRIPTION, '' ),
			Content_Loop_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Content_Loop_Controller::POSTS       => $this->get( Content_Loop::POST_LIST, [] ),
			Content_Loop_Controller::LAYOUT      => $this->get( Content_Loop::LAYOUT, Content_Loop::LAYOUT_ROW ),
		];
	}

}
