<?php
declare( strict_types=1 );

namespace Tribe\Project\Admin;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Admin\Editor\Editor_Styles;

class Admin_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->editor();

		// several 3rd-party plugins trigger errors in the admin due to use of deprecated hooks
		add_filter( 'deprecated_hook_trigger_error', '__return_false' );
	}


	private function editor(): void {
		$this->editor_styles();
		//$this->editor_formats();
	}

	private function editor_styles() {
		add_filter( 'block_editor_settings', function ( $settings ) {
			return $this->container->get( Editor_Styles::class )->remove_core_block_editor_styles( $settings );
		}, 10, 1 );
		add_action( 'enqueue_block_editor_assets', function () {
			$this->container->get( Editor_Styles::class )->enqueue_block_editor_styles();
		}, 10, 0 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) {
			return $this->container->get( Editor_Styles::class )->mce_editor_body_class( $settings );
		}, 10, 1 );
		add_action( 'admin_init', function () {
			return $this->container->get( Editor_Styles::class )->enqueue_mce_editor_styles();
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

}
