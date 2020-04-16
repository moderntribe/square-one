<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Footer;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Footer\Site_Footer as Footer_Context;
use Tribe\Project\Templates\Controllers\Footer\Navigation;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Site_Footer extends Abstract_Controller {
	use Copyright;

	/**
	 * @var Navigation
	 */
	private $navigation;

	public function __construct( Component_Factory $factory, Navigation $navigation ) {
		parent::__construct( $factory );
		$this->navigation = $navigation;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Footer_Context::class, [
			Footer_Context::NAVIGATION => $this->navigation->render(),
			Footer_Context::SOCIAL     => $this->get_social_follow(),
			Footer_Context::COPYRIGHT  => $this->get_copyright(),
			Footer_Context::HOME_URL   => home_url( '/' ),
			Footer_Context::BLOG_NAME  => get_bloginfo( 'name' ),
		] )->render( $path );
	}

	/**
	 * @return array
	 */
	protected function get_social_follow(): array {
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
