<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Theme\Config\Supports;
use Tribe\Project\Theme\Editor\Classic_Editor_Formats;
use Tribe\Project\Theme\Editor\Editor_Styles;
use Tribe\Project\Theme\Nav\Nav_Attribute_Filters;
use Tribe\Project\Theme\Resources\Emoji_Disabler;
use Tribe\Project\Theme\Resources\Legacy_Check;
use Tribe\Project\Theme\Resources\Login_Resources;
use Tribe\Project\Theme\Resources\Scripts;
use Tribe\Project\Theme\Resources\Styles;

class Theme_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->body_classes();
		// $this->full_size_gif(); Uncomment to require full size gifs
		$this->image_sizes();
		$this->image_wrap();
		$this->image_links();
		$this->disable_responsive_images();
		$this->oembed();
		$this->supports();

		$this->login_resources();
		$this->legacy_resources();
		$this->disable_emoji();

		$this->fonts();

		$this->scripts();
		$this->styles();
		$this->editor();

		$this->nav_attributes();
	}

	private function body_classes() {
		add_filter( 'body_class', function ( $classes ) {
			return $this->container->get( Body_Classes::class )->body_classes( $classes );
		}, 10, 1 );
	}

	private function full_size_gif() {
		add_filter( 'image_downsize', function ( $data, $id, $size ) {
			return $this->container->get( Full_Size_Gif::class )->full_size_only_gif( $data, $id, $size );
		}, 10, 3 );
	}

	private function image_sizes() {
		add_action( 'after_setup_theme', function () {
			$this->container->get( Image_Sizes::class )->register_sizes();
		}, 10, 0 );
	}

	private function image_wrap() {
		add_filter( 'the_content', function ( $html ) {
			return $this->container->get( Image_Wrap::class )->customize_wp_image_non_captioned_output( $html );
		}, 12, 1 );
		add_filter( 'the_content', function ( $html ) {
			return $this->container->get( Image_Wrap::class )->customize_wp_image_captioned_output( $html );
		}, 12, 1 );
	}

	private function image_links() {
		add_filter( 'pre_option_image_default_link_type', function () {
			return 'none';
		}, 10, 1 );
	}

	private function disable_responsive_images() {
		add_action( 'init', function () {
			$this->container->get( WP_Responsive_Image_Disabler::class )->hook();
		} );
	}

	private function oembed() {
		add_filter( 'oembed_dataparse', function ( $html, $data, $url ) {
			return $this->container->get( Oembed_Filter::class )->get_video_component( $html, $data, $url );
		}, 999, 3 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) {
			return $this->container->get( Oembed_Filter::class )->filter_frontend_html_from_cache( $html, $url, $attr, $post_id );
		}, 1, 4 );

		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) {
			return $this->container->get( Oembed_Filter::class )->wrap_admin_oembed( $html, $url, $attr, $post_id );
		}, 99, 4 );
	}

	private function supports() {
		add_action( 'after_setup_theme', function () {
			$this->container->get( Supports::class )->add_theme_supports();
		}, 10, 0 );
	}

	private function login_resources() {
		add_action( 'login_enqueue_scripts', function () {
			$this->container->get( Login_Resources::class )->login_styles();
		}, 10, 0 );
	}

	private function legacy_resources() {
		add_action( 'wp_head', function () {
			$this->container->get( Legacy_Check::class )->old_browsers();
		}, 0, 0 );

		add_action( 'init', function () {
			$this->container->get( Legacy_Check::class )->add_unsupported_rewrite();
		} );

		add_filter( 'template_include', function ( $template ) {
			return $this->container->get( Legacy_Check::class )->load_unsupported_template( $template );
		} );
	}

	private function disable_emoji() {
		add_action( 'after_setup_theme', function () {
			$this->container->get( Emoji_Disabler::class )->remove_hooks();
		} );
	}

	private function fonts() {
		add_action( 'wp_head', function () {
			$this->container->get( Web_Fonts::class )->load_fonts();
		}, 0, 0 );
		add_action( 'tribe/unsupported_browser/head', function () {
			$this->container->get( Web_Fonts::class )->load_fonts();
		}, 0, 0 );
		add_action( 'admin_head', function () {
			$this->container->get( Web_Fonts::class )->localize_typekit_tinymce();
		}, 0, 0 );
		add_filter( 'mce_external_plugins', function ( $plugins ) {
			return $this->container->get( Web_Fonts::class )->add_typekit_to_editor( $plugins );
		}, 10, 1 );
		/* add_action( 'login_head', function() {
			$this->container->get( Fonts::class )->load_fonts();
		}, 0, 0); */
	}

	private function scripts() {
		add_action( 'wp_head', function () {
			$this->container->get( Scripts::class )->maybe_inject_bugsnag();
		}, 0, 0 );
		add_action( 'wp_head', function () {
			$this->container->get( Scripts::class )->set_preloading_tags();
		}, 10, 0 );
		add_action( 'wp_footer', function () {
			$this->container->get( Scripts::class )->add_early_polyfills();
		}, 10, 0 );
		add_action( 'wp_enqueue_scripts', function () {
			$this->container->get( Scripts::class )->enqueue_scripts();
		}, 10, 0 );
	}

	private function styles() {
		add_action( 'wp_enqueue_scripts', function () {
			$this->container->get( Styles::class )->enqueue_styles();
		}, 10, 0 );
	}

	private function editor(): void {
		$this->editor_styles();
		//$this->editor_formats();
	}

	private function editor_styles() {
		add_action( 'admin_init', function () {
			$this->container->get( Editor_Styles::class )->block_editor_styles();
		}, 10, 0 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) {
			return $this->container->get( Editor_Styles::class )->mce_editor_body_class( $settings );
		}, 10, 1 );
		add_filter( 'editor_stylesheets', function ( $styles ) {
			return $this->container->get( Editor_Styles::class )->mce_editor_styles( $styles );
		}, 10, 1 );
	}

	private function editor_formats() {
		add_filter( 'mce_buttons', function ( $settings ) {
			return $this->container->get( Classic_Editor_Formats::class )->mce_buttons( $settings );
		}, 10, 1 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) {
			return $this->container->get( Classic_Editor_Formats::class )->visual_editor_styles_dropdown( $settings );
		}, 10, 1 );
	}

	private function nav_attributes() {
		add_filter( 'nav_menu_item_id', function ( $menu_id, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_id( $menu_id, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_classes( $classes, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_anchor_atts( $atts, $item, $args, $depth );
		}, 10, 4 );
	}

}
