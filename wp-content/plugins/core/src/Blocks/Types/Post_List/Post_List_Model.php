<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Post_List;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\post_list\Post_List_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Post_List_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Post_List_Controller::ATTRS   => $this->get_attrs(),
			Post_List_Controller::CLASSES => $this->get_classes(),
			Post_List_Controller::POSTS   => $this->get( Post_List::POST_LIST, [] ),
		];
	}
}
