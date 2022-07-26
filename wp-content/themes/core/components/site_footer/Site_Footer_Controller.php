<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\site_footer;

use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Object_Meta\Theme_Options;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\navigation\Navigation_Controller;
use Tribe\Project\Templates\Components\Traits\Copyright;

class Site_Footer_Controller extends Abstract_Controller {

	use Copyright;

	private Theme_Options $settings;

	public function __construct( Theme_Options $settings ) {
		$this->settings = $settings;
	}

	public function get_logo_args(): array {
		return [
			Container_Controller::CLASSES => [ 'c-site-footer__logo' ],
			Container_Controller::CONTENT => $this->get_logo_link(),
		];
	}

	public function get_description_args(): array {
		$description = $this->settings->get_value( Theme_Options::FOOTER_DESCRIPTION );

		if ( empty( $description ) ) {
			return [];
		}

		return [
			Container_Controller::CLASSES => [ 'c-site-footer__description', 't-sink', 's-sink' ],
			Container_Controller::CONTENT => wpautop( wp_kses_post( $description ) ),
		];
	}

	public function get_ctas_args(): array {
		$ctas  = $this->get_cta( Theme_Options::FOOTER_CTA_1 );
		$ctas .= $this->get_cta( Theme_Options::FOOTER_CTA_2 );

		if ( empty( $ctas ) ) {
			return [];
		}

		return [
			Container_Controller::CLASSES => [ 'c-site-footer__ctas' ],
			Container_Controller::CONTENT => $ctas,
		];
	}

	public function get_footer_nav_args(): array {
		return [
			Navigation_Controller::MENU_LOCATION    => Nav_Menus_Definer::FOOTER,
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
			Navigation_Controller::MENU_LOCATION    => Nav_Menus_Definer::LEGAL,
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

	public function get_copyright_args(): array {
		return [
			Container_Controller::CLASSES => [ 'c-site-footer__copyright', 't-input' ],
			Container_Controller::CONTENT => $this->get_copyright(),
		];
	}

	private function get_logo_link(): string {
		$content = ! empty( $this->get_logo_image() ) ? $this->get_logo_image() : get_bloginfo( 'name' );

		return tribe_template_part( 'components/link/link', '', [
			Link_Controller::CLASSES => [ 'c-site-footer__logo-link', 't-display-x-small' ],
			Link_Controller::URL     => home_url( '/' ),
			Link_Controller::CONTENT => $content,
		] );
	}

	private function get_logo_image(): string {
		$image_id = $this->settings->get_value( Theme_Options::FOOTER_LOGO );

		if ( empty( $image_id ) ) {
			return '';
		}

		$args = [
			Image_Controller::IMG_ID       => $image_id,
			Image_Controller::CLASSES      => [ 'c-site-footer__logo-image' ],
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

	private function get_cta( string $key ): string {
		$link = $this->settings->get_value( $key );

		if ( empty( $link['url'] ) ) {
			return '';
		}

		$args = [
			Link_Controller::URL     => $link['url'],
			Link_Controller::CONTENT => $link['title'],
			Link_Controller::TARGET  => $link['target'],
			Link_Controller::CLASSES => [ 'c-site-footer_cta', 'a-btn-secondary' ],
		];

		return tribe_template_part( 'components/link/link', '', $args );
	}

}
