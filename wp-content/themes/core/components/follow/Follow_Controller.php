<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\follow;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Settings\General;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Collections\Social_Link_Collection;
use Tribe\Project\Templates\Models\Social_Link;

class Follow_Controller extends Abstract_Controller {

	protected General $settings;

	/**
	 * Change the order of this array to change the display order.
	 *
	 * @var string[]
	 */
	private array $social_keys = [
		Social_Settings::FACEBOOK,
		Social_Settings::TWITTER,
		Social_Settings::YOUTUBE,
		Social_Settings::LINKEDIN,
		Social_Settings::PINTEREST,
		Social_Settings::INSTAGRAM,
	];

	public function __construct( General $settings ) {
		$this->settings = $settings;
	}

	public function get_social_links(): Social_Link_Collection {
		$links = array_filter( array_map( function ( $social_site ) {
			$url = $this->settings->get_setting( $social_site );

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
			case Social_Settings::FACEBOOK:
				return __( 'Like us on Facebook', 'tribe' );

			case Social_Settings::TWITTER:
				return __( 'Follow us on Twitter', 'tribe' );

			case Social_Settings::YOUTUBE:
				return __( 'Follow us on YouTube', 'tribe' );

			case Social_Settings::LINKEDIN:
				return __( 'Add us on LinkedIn', 'tribe' );

			case Social_Settings::PINTEREST:
				return __( 'Follow us on Pinterest', 'tribe' );

			case Social_Settings::INSTAGRAM:
				return __( 'Follow us on Instagram', 'tribe' );

			default:
				return '';
		}
	}

}
