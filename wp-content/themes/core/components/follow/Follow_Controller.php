<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\follow;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Social_Link;

class Follow_Controller extends Abstract_Controller {

	// Change the order of this array to change the display order
	private array $social_keys = [
		Social_Settings::FACEBOOK,
		Social_Settings::TWITTER,
		Social_Settings::YOUTUBE,
		Social_Settings::LINKEDIN,
		Social_Settings::PINTEREST,
		Social_Settings::INSTAGRAM,
	];

	/**
	 * @return Social_Link[]
	 */
	public function get_social_links(): array {
		$links = [];

		foreach ( $this->social_keys as $social_site ) {
			$social_link = get_field( $social_site, 'option' );

			if ( ! empty( $social_link ) ) {
				$links[] = new Social_Link(
					$social_site,
					$social_link,
					Social_Settings::get_social_follow_message( $social_site )
				);
			}
		}

		return $links;
	}

}
