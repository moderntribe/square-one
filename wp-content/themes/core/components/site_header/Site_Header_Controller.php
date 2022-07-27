<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\site_header;

use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Nav_Menus\Walker\Walker_Nav_Menu_Primary;
use Tribe\Project\Object_Meta\Theme_Options;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\button\Button_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\navigation\Navigation_Controller;
use Tribe\Project\Templates\Components\search_form\Search_Form_Controller;

class Site_Header_Controller extends Abstract_Controller {

	private Theme_Options $settings;

	public function __construct( Theme_Options $settings ) {
		$this->settings = $settings;
	}

	public function get_logo_args(): array {
		return [
			Container_Controller::CLASSES => [ 'c-site-header__logo' ],
			Container_Controller::CONTENT => $this->get_logo_link(),
		];
	}

	public function get_flyout_toggle_args(): array {
		return [
			Button_Controller::CONTENT    => '<i class="c-site-header__flyout-toggle-icon icon icon-hamburger"></i>',
			Button_Controller::ARIA_LABEL => esc_attr__( 'Toggle site navigation', 'tribe' ),
			Button_Controller::CLASSES    => [ 'c-site-header__flyout-toggle' ],
			Button_Controller::ATTRS      => [
				'id'            => 'flyout-toggle',
				'data-js'       => 'flyout-toggle',
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'aria-controls' => 'site-header-flyout',
			],
		];
	}

	public function get_main_nav_args(): array {
		return [
			Navigation_Controller::MENU_LOCATION    => Nav_Menus_Definer::PRIMARY,
			Navigation_Controller::CLASSES          => [ 'c-nav', 'c-nav-main' ],
			Navigation_Controller::NAV_LIST_CLASSES => [ 'c-nav-main__list' ],
			Navigation_Controller::ATTRS            => [
				'data-js'    => 'c-nav-main',
				'id'         => 'site-header-flyout',
				'aria-label' => esc_attr__( 'Main navigation menu', 'tribe' ),
			],
			Navigation_Controller::NAV_MENU_ARGS    => [
				'depth'  => 2,
				'walker' => new Walker_Nav_Menu_Primary(),
			],
		];
	}

	public function get_search_toggle_args(): array {
		return [
			Button_Controller::CONTENT    => '<i class="c-site-header__search-toggle-icon icon icon-search"></i>',
			Button_Controller::ARIA_LABEL => esc_attr__( 'Toggle site search', 'tribe' ),
			Button_Controller::CLASSES    => [ 'c-site-header__search-toggle' ],
			Button_Controller::ATTRS      => [
				'id'            => 'search-toggle',
				'data-js'       => 'search-toggle',
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'aria-controls' => 'search-flyout',
			],
		];
	}

	public function get_search_form_args(): array {
		return [
			Search_Form_Controller::CLASSES     => [ 'c-search--site-header' ],
			Search_Form_Controller::FORM_ID     => uniqid( 's-' ),
			Search_Form_Controller::PLACEHOLDER => esc_attr__( 'Search this site', 'tribe' ),
			Search_Form_Controller::VALUE       => get_search_query(),
		];
	}

	private function get_logo_link(): string {
		$content = ! empty( $this->get_logo_image() ) ? $this->get_logo_image() : get_bloginfo( 'name' );

		return tribe_template_part( 'components/link/link', '', [
			Link_Controller::CLASSES => [ 'c-site-header__logo-link', 't-display-x-small' ],
			Link_Controller::URL     => home_url( '/' ),
			Link_Controller::CONTENT => $content,
		] );
	}

	private function get_logo_image(): string {
		$image_id = $this->settings->get_value( Theme_Options::MASTHEAD_LOGO );

		if ( empty( $image_id ) ) {
			return '';
		}

		$args = [
			Image_Controller::IMG_ID       => $image_id,
			Image_Controller::CLASSES      => [ 'c-site-header__logo-image' ],
			Image_Controller::AUTO_SHIM    => false,
			Image_Controller::SRC_SIZE     => 'medium',
			Image_Controller::SRCSET_SIZES => [
				'medium',
				'medium_large',
				'large',
			],
		];

		return tribe_template_part( 'components/image/image', '', $args );
	}

}
