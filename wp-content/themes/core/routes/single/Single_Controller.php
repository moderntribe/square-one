<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\single;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Single_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Single_Controller extends Abstract_Controller {

	use Page_Title;

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	public function get_subheader_args(): array {
		global $post;

		return [
			Subheader_Single_Controller::TITLE                => $this->get_page_title(),
			Subheader_Single_Controller::DATE                 => get_the_date(),
			Subheader_Single_Controller::AUTHOR               => get_the_author_meta( 'display_name', $post->post_author ),
			Subheader_Single_Controller::SHOULD_RENDER_BYLINE => true,
		];
	}

}
