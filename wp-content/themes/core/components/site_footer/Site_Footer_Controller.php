<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\footer\site_footer;

use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\navigation\Navigation_Controller;
use Tribe\Project\Templates\Components\Traits\Copyright;

class Site_Footer_Controller extends Abstract_Controller {

	use Copyright;

	public function get_footer_nav_args(): array {
		return [
			Navigation_Controller::LOCATION         => Nav_Menus_Definer::FOOTER,
			Navigation_Controller::CLASSES          => [ 'c-nav', 'c-nav-footer' ],
			Navigation_Controller::NAV_LIST_CLASSES => [ 'c-nav-footer__list' ],
			Navigation_Controller::ATTRS            => [
				'aria-label' => esc_html__( 'Footer', 'tribe' ),
			],
			Navigation_Controller::NAV_MENU_ARGS    => [
				'depth' => 2,
			],
		];
	}

	public function get_legal_nav_args(): array {
		return [
			Navigation_Controller::LOCATION         => Nav_Menus_Definer::LEGAL,
			Navigation_Controller::CLASSES          => [ 'c-nav', 'c-nav-legal' ],
			Navigation_Controller::NAV_LIST_CLASSES => [ 'c-nav-legal__list' ],
			Navigation_Controller::ATTRS            => [
				'aria-label' => esc_html__( 'Legal', 'tribe' ),
			],
			Navigation_Controller::NAV_MENU_ARGS    => [
				'depth' => 1,
			],
		];
	}

}
