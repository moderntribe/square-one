<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\follow;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Collections\Social_Link_Collection;
use Tribe\Project\Templates\Models\Social_Link;
use Tribe\Project\Theme_Customizer\Customizer_Sections\Social_Follow_Settings;

class Follow_Controller extends Abstract_Controller {

	/**
	 * Change the order of this array to change the display order.
	 *
	 * @var string[]
	 */
	private array $social_keys = [
		Social_Follow_Settings::SOCIAL_FACEBOOK,
		Social_Follow_Settings::SOCIAL_TWITTER,
		Social_Follow_Settings::SOCIAL_YOUTUBE,
		Social_Follow_Settings::SOCIAL_LINKEDIN,
		Social_Follow_Settings::SOCIAL_PINTEREST,
		Social_Follow_Settings::SOCIAL_INSTAGRAM,
	];

	public function get_social_links(): Social_Link_Collection {
		$links = array_filter( array_map( function ( $social_site ) {
			$url = get_theme_mod( $social_site );

			if ( ! $url ) {
				return [];
			}

			$link        = new Social_Link();
			$link->title = $this->get_label( $social_site );
			$link->url   = $url;
			$link->class = $social_site;

			return $link->toArray();
		}, $this->social_keys ) );

		return Social_Link_Collection::create( $links );
	}

	protected function get_label( string $site ): string {
		switch ( $site ) {
			case Social_Follow_Settings::SOCIAL_FACEBOOK:
				return esc_html__( 'Like us on Facebook', 'tribe' );

			case Social_Follow_Settings::SOCIAL_TWITTER:
				return esc_html__( 'Follow us on Twitter', 'tribe' );

			case Social_Follow_Settings::SOCIAL_YOUTUBE:
				return esc_html__( 'Follow us on YouTube', 'tribe' );

			case Social_Follow_Settings::SOCIAL_LINKEDIN:
				return esc_html__( 'Add us on LinkedIn', 'tribe' );

			case Social_Follow_Settings::SOCIAL_PINTEREST:
				return esc_html__( 'Follow us on Pinterest', 'tribe' );

			case Social_Follow_Settings::SOCIAL_INSTAGRAM:
				return esc_html__( 'Follow us on Instagram', 'tribe' );

			default:
				return '';
		}
	}

}
