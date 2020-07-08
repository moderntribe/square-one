<?php

namespace Tribe\Project\Templates\Components\Follow;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Components\Component;

class Follow extends Component {

	const LINKS = 'links';

	public function init() {
		$this->data[ self::LINKS ] = $this->get_social_links();
	}

	/**
	 * @return array
	 */
	protected function get_social_links(): array {
		$links = [];

		// Change the order of this array to change the display order
		$social_keys = [
			Social_Settings::FACEBOOK,
			Social_Settings::TWITTER,
			Social_Settings::YOUTUBE,
			Social_Settings::LINKEDIN,
			Social_Settings::PINTEREST,
			Social_Settings::INSTAGRAM,
		];

		foreach ( $social_keys as $social_site ) {
			$social_link = get_field( $social_site, 'option' );

			if ( ! empty( $social_link ) ) {
				$links[ $social_site ] = [
					'url'   => $social_link,
					'title' => Social_Settings::get_social_follow_message( $social_site ),
				];
			}
		}

		return $links;
	}

}
