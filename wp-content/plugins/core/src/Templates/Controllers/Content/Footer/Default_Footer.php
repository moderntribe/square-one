<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Footer;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content\Navigation\Footer as Navigation;
use Tribe\Project\Templates\Controllers\Traits\Copyright;
use Twig\Environment;

class Default_Footer extends Abstract_Template {
	use Copyright;

	protected $path = 'content/footer/default.twig';

	/**
	 * @var Navigation
	 */
	private $navigation;

	public function __construct( Environment $twig, Component_Factory $factory, Navigation $navigation ) {
		parent::__construct( $twig, $factory );
		$this->navigation = $navigation;
	}

	public function get_data(): array {
		return [
			'navigation'    => $this->navigation->render(),
			'social_follow' => $this->get_social_follow(),
			'copyright'     => $this->get_copyright(),
			'home_url'      => home_url( '/' ),
			'name'          => get_bloginfo( 'name' ),
		];
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
