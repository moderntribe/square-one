<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Theme\Config\Supports;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Media\Image_Wrap;
use Tribe\Project\Theme\Media\Oembed_Filter;

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
		$this->oembed();
	}

	private function body_classes() {
		add_filter( 'body_class', function ( $classes ) {
			return $this->container->get( Body_Classes::class )->body_classes( $classes );
		}, 10, 1 );
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
		add_action( 'wp_enqueue_scripts', function () {
			$this->container->get( Web_Fonts::class )->enqueue_fonts();
		}, 0, 0 );
		add_action( 'enqueue_block_editor_assets', function () {
			$this->container->get( Web_Fonts::class )->enqueue_fonts();
		}, 0, 0 );
		add_action( 'tribe/unsupported_browser/head', function () {
			$this->container->get( Web_Fonts::class )->inject_unsupported_browser_fonts();
		}, 0, 0 );
		add_action( 'after_setup_theme', function () {
			$this->container->get( Web_Fonts::class )->add_tinymce_editor_fonts();
		}, 9, 0 );
	}

}
