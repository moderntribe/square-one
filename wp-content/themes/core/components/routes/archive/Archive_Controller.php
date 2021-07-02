<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\archive;

use Tribe\Project\Object_Meta\Post_Archive_Settings;
use Tribe\Project\Templates\Components\header\subheader_archive\Subheader_Archive_Controller;
use Tribe\Project\Templates\Components\routes\index\Index_Controller;

class Archive_Controller extends Index_Controller {

	/**
	 * Prepare data for the Subheader_Archive Component
	 *
	 * @see Subheader_Archive_Controller, `subheader_archive.php`
	 *
	 * @return array
	 */
	public function get_subheader_archive_data(): array {
		$args = [];

		if ( is_category() ) {
			$term = get_queried_object();

			if ( ! empty( $term ) ) {
				$args[ Subheader_Archive_Controller::TITLE ]       = $term->name;
				$args[ Subheader_Archive_Controller::DESCRIPTION ] = $term->category_description;

				$hero_image = get_field( Post_Archive_Settings::HERO_IMAGE, $term->taxonomy.'_'.$term->term_id );
				if ( ! empty( $hero_image ) ) {
					$args[ Subheader_Archive_Controller::HERO_IMAGE_ID ] = $hero_image['ID'];
				}
			}

			return $args;
		}

		$title = get_field( 'title', 'option' );
		if ( ! empty( $title ) ) {
			$args[ Subheader_Archive_Controller::TITLE ] = $title;
		}

		$description = get_field( 'description', 'option' );
		if ( ! empty( $description ) ) {
			$args[ Subheader_Archive_Controller::DESCRIPTION ] = $description;
		}

		$hero_image = get_field( 'hero_image', 'option' );
		if ( ! empty( $hero_image ) ) {
			$args[ Subheader_Archive_Controller::HERO_IMAGE_ID ] = $hero_image['ID'];
		}

		return $args;
	}

}
