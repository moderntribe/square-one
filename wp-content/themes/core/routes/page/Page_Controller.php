<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\page;

use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;
use Tribe\Project\Templates\Routes\index\Index_Controller;

class Page_Controller extends Index_Controller {

	public string $sidebar_id = '';

	public function get_subheader_args(): array {
		global $post;

		$args                                = [];
		$args[ Subheader_Controller::TITLE ] = $this->get_page_title();
		$hero_image_id                       = (int) get_post_thumbnail_id( $post->ID );

		if ( ! empty( $hero_image_id ) ) {
			$args[ Subheader_Controller::HERO_IMAGE ] = new Image( (array) acf_get_attachment( $hero_image_id ) );
		}

		return $args;
	}

}
