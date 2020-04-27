<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Theme\Config\Supports;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Media\Full_Size_Gif;
use Tribe\Project\Theme\Media\Image_Wrap;
use Tribe\Project\Theme\Media\Oembed_Filter;
use Tribe\Project\Theme\Media\WP_Responsive_Image_Disabler;

class Theme_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->body_classes();
		$this->media();
		$this->config();
	}

	private function config(): void {
		$this->supports();
		$this->image_sizes();
		$this->web_fonts();
	}

	private function media(): void {
		$this->image_wrap();
		$this->image_links();
		$this->disable_responsive_images();
		//$this->oembed();
		// $this->full_size_gif(); // Uncomment to require full size gifs
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
		add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
			return $this->container->get( WP_Responsive_Image_Disabler::class )->filter_image_attributes( $attr );
		}, 999, 1 );
		add_action( 'after_setup_theme', function () {
			$this->container->get( WP_Responsive_Image_Disabler::class )->disable_wordpress_filters();
		}, 10, 0 );
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

	private function web_fonts() {
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

}
