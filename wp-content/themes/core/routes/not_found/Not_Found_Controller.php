<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\not_found;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\search_form\Search_Form_Controller;

class Not_Found_Controller extends Abstract_Controller {

	public const SIDEBAR_ID = 'sidebar_id';

	protected string $sidebar_id;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->sidebar_id = (string) $args[ self::SIDEBAR_ID ];
	}

	public function get_search_form_args(): array {
		return [
			Search_Form_Controller::FORM_ID     => uniqid( 's-' ),
			Search_Form_Controller::PLACEHOLDER => __( 'Search', 'tribe' ),
		];
	}

	public function get_sidebar_id(): string {
		return $this->sidebar_id;
	}

	protected function defaults(): array {
		return [
			self::SIDEBAR_ID => '',
		];
	}

}
