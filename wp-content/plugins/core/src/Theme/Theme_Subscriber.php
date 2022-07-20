<?php declare(strict_types=1);

namespace Tribe\Project\Theme;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Theme\Branding\Brand_Meta;
use Tribe\Project\Theme\Branding\Customizer_Settings;
use Tribe\Project\Theme\Branding\Login_Screen;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Theme\Config\Supports;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Media\Image_Attributes;
use Tribe\Project\Theme\Media\Oembed_Filter;

class Theme_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		$this->body_classes();
		$this->site_branding();
		$this->media();
		$this->config();
	}

	private function site_branding(): void {
		$this->brand_meta();
		$this->customizer_settings();
		$this->login_screen();
	}

	private function config(): void {
		$this->supports();
		$this->image_sizes();
		$this->web_fonts();
	}

	private function media(): void {
		$this->image_attrs();
		$this->image_links();
		$this->oembed();
	}

	private function body_classes(): void {
		add_filter( 'body_class', function ( $classes ) {
			return $this->container->get( Body_Classes::class )->body_classes( (array) $classes );
		}, 10, 1 );
	}

	private function brand_meta(): void {
		add_action( 'wp_head', function (): void {
			$this->container->get( Brand_Meta::class )->inject_android_theme_color_meta();
		}, 10, 0 );
	}

	private function customizer_settings(): void {
		add_action( 'customize_register', function ( $wp_customize ): void {
			$this->container->get( Customizer_Settings::class )->register_customizer_controls( $wp_customize );
		}, 20, 1 );
	}

	private function login_screen(): void {
		add_action( 'login_enqueue_scripts', function (): void {
			$this->container->get( Login_Screen::class )->inject_login_logo();
		} );

		add_filter( 'login_headerurl', function () {
			return $this->container->get( Login_Screen::class )->customize_login_header_url();
		}, 10, 0 );

		add_filter( 'login_headertext', function () {
			return $this->container->get( Login_Screen::class )->customize_login_header_title();
		}, 10, 0 );
	}

	private function image_sizes(): void {
		add_action( 'after_setup_theme', function (): void {
			$this->container->get( Image_Sizes::class )->register_sizes();
		}, 10, 0 );
	}

	private function image_attrs(): void {
		add_filter( 'wp_img_tag_add_loading_attr', function ( $value, string $image, string $context ) {
			return $this->container->get( Image_Attributes::class )->customize_wp_image_loading_attr( $value, $image, $context );
		}, 10, 3 );
	}

	private function image_links(): void {
		add_filter( 'pre_option_image_default_link_type', static function () {
			return 'none';
		}, 10, 1 );
	}

	private function oembed(): void {
		// Fix reusable blocks from not rendering embeds on the frontend by re-running autoembed at a higher priority than do_blocks()
		add_filter( 'the_content', static function ( $content ) {
			if ( is_admin() ) {
				return $content;
			}

			return $GLOBALS['wp_embed']->autoembed( $content );
		}, 10, 1 );

		add_filter( 'oembed_dataparse', function ( $html, $data, $url ) {
			return $this->container->get( Oembed_Filter::class )->get_video_component( (string) $html, (object) $data, (string) $url );
		}, 999, 3 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr ) {
			return $this->container->get( Oembed_Filter::class )->filter_frontend_html_from_cache( $html, (string) $url, (array) $attr );
		}, 1, 3 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) {
			return $this->container->get( Oembed_Filter::class )->wrap_admin_oembed( $html, (string) $url, (array) $attr, (int) $post_id );
		}, 99, 4 );
	}

	private function supports(): void {
		add_action( 'after_setup_theme', function (): void {
			$this->container->get( Supports::class )->add_theme_supports();
		}, 10, 0 );
	}

	private function web_fonts(): void {
		add_action( 'wp_enqueue_scripts', function (): void {
			$this->container->get( Web_Fonts::class )->enqueue_fonts();
		}, 0, 0 );
		add_action( 'enqueue_block_editor_assets', function (): void {
			$this->container->get( Web_Fonts::class )->enqueue_fonts();
		}, 0, 0 );
		add_action( 'after_setup_theme', function (): void {
			$this->container->get( Web_Fonts::class )->add_tinymce_editor_fonts();
		}, 9, 0 );
	}

}
