<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Repository;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller;

class Content_Loop_Model extends Base_Model {

	protected Post_Loop_Repository $post_loop;

	public function __construct( array $block, Post_Loop_Repository $post_loop ) {
		$this->post_loop = $post_loop;

		parent::__construct( $block );
	}

	public function init_data(): array {
		return [
			Content_Loop_Controller::ATTRS       => $this->get_attrs(),
			Content_Loop_Controller::CLASSES     => $this->get_classes(),
			Content_Loop_Controller::CTA         => new Cta( $this->get( Cta_Field::GROUP_CTA, [] ) ),
			Content_Loop_Controller::DESCRIPTION => $this->get( Content_Loop::DESCRIPTION, '' ),
			Content_Loop_Controller::LAYOUT      => $this->get( Content_Loop::LAYOUT, Content_Loop::LAYOUT_ROW ),
			Content_Loop_Controller::LEADIN      => $this->get( Content_Loop::LEADIN, '' ),
			Content_Loop_Controller::POSTS       => $this->post_loop->get_posts( (array) $this->get( Content_Loop::POST_LIST ) ),
			Content_Loop_Controller::TITLE       => $this->get( Content_Loop::TITLE, '' ),
		];
	}

}
