<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\follow;

use Tribe\Project\Object_Meta\Theme_Options;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Collections\Social_Link_Collection;
use Tribe\Project\Templates\Models\Social_Link;

class Follow_Controller extends Abstract_Controller {

	private Theme_Options $settings;

	/**
	 * Change the order of this array to change the display order.
	 *
	 * @var string[]
	 */
	private array $social_keys = [
		Theme_Options::SOCIAL_FACEBOOK,
		Theme_Options::SOCIAL_TWITTER,
		Theme_Options::SOCIAL_YOUTUBE,
		Theme_Options::SOCIAL_LINKEDIN,
		Theme_Options::SOCIAL_PINTEREST,
		Theme_Options::SOCIAL_INSTAGRAM,
	];

	public function __construct( Theme_Options $settings ) {
		$this->settings = $settings;
	}

	public function get_social_links(): Social_Link_Collection {
		$links = array_filter( array_map( function ( $social_site ) {
			$url = $this->settings->get_value( $social_site );

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
			case Theme_Options::SOCIAL_FACEBOOK:
				return esc_html__( 'Like us on Facebook', 'tribe' );

			case Theme_Options::SOCIAL_TWITTER:
				return esc_html__( 'Follow us on Twitter', 'tribe' );

			case Theme_Options::SOCIAL_YOUTUBE:
				return esc_html__( 'Follow us on YouTube', 'tribe' );

			case Theme_Options::SOCIAL_LINKEDIN:
				return esc_html__( 'Add us on LinkedIn', 'tribe' );

			case Theme_Options::SOCIAL_PINTEREST:
				return esc_html__( 'Follow us on Pinterest', 'tribe' );

			case Theme_Options::SOCIAL_INSTAGRAM:
				return esc_html__( 'Follow us on Instagram', 'tribe' );

			default:
				return '';
		}
	}

}
