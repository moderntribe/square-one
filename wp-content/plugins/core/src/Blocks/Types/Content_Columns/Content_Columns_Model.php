<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Columns;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\content_columns\Content_Columns_Block_Controller;
use Tribe\Project\Templates\Models\Collections\Content_Column_Collection;

class Content_Columns_Model extends Base_Model {

	public function get_data(): array {
		return [
			Content_Columns_Block_Controller::ATTRS         => $this->get_attrs(),
			Content_Columns_Block_Controller::CLASSES       => $this->get_classes(),
			Content_Columns_Block_Controller::TITLE         => $this->get( Content_Columns::TITLE, '' ),
			Content_Columns_Block_Controller::LEADIN        => $this->get( Content_Columns::LEADIN, '' ),
			Content_Columns_Block_Controller::DESCRIPTION   => $this->get( Content_Columns::DESCRIPTION, '' ),
			Content_Columns_Block_Controller::CTA           => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Content_Columns_Block_Controller::COLUMNS       => Content_Column_Collection::create( $this->get( Content_Columns::COLUMNS, [] ) ),
			Content_Columns_Block_Controller::CONTENT_ALIGN => $this->get( Content_Columns::CONTENT_ALIGN, Content_Columns::CONTENT_ALIGN_LEFT ),
		];
	}

}
