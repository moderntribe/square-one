<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\site_footer;

use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\navigation\Navigation_Controller;
use Tribe\Project\Templates\Components\Traits\Copyright;
use Tribe\Project\Theme_Customizer\Customizer_Sections\Footer_Settings;

class Site_Footer_Controller extends Abstract_Controller {

	use Copyright;

	public function get_logo_args(): array {
		return [
			Container_Controller::CLASSES => [ 'c-site-footer__logo' ],
			Container_Controller::CONTENT => $this->get_logo_link(),
		];
	}

	public function get_description_args(): array {
		$description = get_theme_mod( Footer_Settings::FOOTER_DESCRIPTION );

		if ( empty( $description ) ) {
			return [];
		}

		return [
			Container_Controller::CLASSES => [ 'c-site-footer__description' ],
			Container_Controller::CONTENT => wp_kses( $description, Footer_Settings::FOOTER_ALLOWED_HTML ),
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
			Container_Controller::CLASSES => [ 'c-site-footer__copyright' ],
			Container_Controller::CONTENT => $this->get_copyright(),
		];
	}

	private function get_logo_link(): string {
		$content = ! empty( $this->get_logo_image() ) ? $this->get_logo_image() : get_bloginfo( 'name' );

		return tribe_template_part( 'components/link/link', '', [
			Link_Controller::CLASSES => [ 'c-site-footer__logo-link' ],
			Link_Controller::URL     => home_url( '/' ),
			Link_Controller::CONTENT => $content,
		] );
	}

	private function get_logo_image(): string {
		$image_id = get_theme_mod( Footer_Settings::FOOTER_LOGO );

		if ( empty( $image_id ) ) {
			return '';
		}

		return strpos( get_post_mime_type( $image_id ), 'image/svg' )
			? $this->get_logo_image_svg_src( $image_id )
			: $this->get_logo_image_default_src( $image_id );
	}


	private function get_logo_image_svg_src( int $image_id ): string {
		$image_path = get_attached_file( $image_id );

		return file_exists( $image_path ) ? file_get_contents( $image_path ) : '';
	}

	private function get_logo_image_default_src( int $image_id ): string {
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

}
