<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\not_found;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\search_form\Search_Form_Controller;

class Not_Found_Controller extends Abstract_Controller {

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	/**
	 * @return array
	 */
	public function get_search_form_args(): array {
		return [
			Search_Form_Controller::FORM_ID     => uniqid( 's-' ),
			Search_Form_Controller::PLACEHOLDER => __( 'Search', 'tribe' ),
		];
	}

}
