<?php
declare( strict_types=1 );

namespace Tribe\Project\Assets;

use Tribe\Libs\Container\Abstract_Subscriber;

class Assets_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->theme_resources();
		$this->legacy_resources();
		$this->admin_resources();
	}


	private function theme_resources(): void {
		add_action( 'template_redirect', function () {
			$this->container->get( Theme\Scripts::class )->register_scripts();
			$this->container->get( Theme\Styles::class )->register_styles();
		}, 10, 0 );

		add_action( 'wp_enqueue_scripts', function () {
			$this->container->get( Theme\Scripts::class )->enqueue_scripts();
			$this->container->get( Theme\Styles::class )->enqueue_styles();
		}, 10, 0 );

		add_action( 'wp_head', function () {
			$this->container->get( Theme\Scripts::class )->maybe_inject_bugsnag();
		}, 0, 0 );
		add_action( 'wp_head', function () {
			$this->container->get( Theme\Scripts::class )->set_preloading_tags();
		}, 10, 0 );
		add_action( 'wp_footer', function () {
			$this->container->get( Theme\Scripts::class )->add_early_polyfills();
		}, 10, 0 );
	}

	private function legacy_resources(): void {
		add_action( 'wp_head', function () {
			$this->container->get( Theme\Legacy_Check::class )->print_redirect_script();
		}, 0, 0 );

		add_action( 'init', function () {
			$this->container->get( Theme\Legacy_Check::class )->add_unsupported_rewrite();
		} );

		add_filter( 'template_include', function ( $template ) {
			return $this->container->get( Theme\Legacy_Check::class )->load_unsupported_template( $template );
		} );
	}

	private function admin_resources(): void {
		add_action( 'current_screen', function( $current_screen ) {
			$this->container->get( Admin\Scripts::class )->register_scripts();
			$this->container->get( Admin\Styles::class )->register_styles();
		}, 10, 1 );


		add_action( 'admin_enqueue_scripts', function () {
			$this->container->get( Admin\Scripts::class )->enqueue_scripts();
			$this->container->get( Admin\Styles::class )->enqueue_styles();
		}, 10, 0 );
	}
}
