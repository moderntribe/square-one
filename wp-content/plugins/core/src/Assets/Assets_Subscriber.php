<?php
declare( strict_types=1 );

namespace Tribe\Project\Assets;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Assets\Theme\Scripts;
use Tribe\Project\Assets\Theme\Styles;

class Assets_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->theme_scripts();
		$this->theme_styles();
	}


	private function theme_scripts(): void {
		add_action( 'template_redirect', function() {
			$this->container->get( Scripts::class )->register_scripts();
		}, 10, 0 );
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

	private function theme_styles(): void {
		add_action( 'template_redirect', function() {
			$this->container->get( Styles::class )->register_styles();
		}, 10, 0 );
		add_action( 'wp_enqueue_scripts', function () {
			$this->container->get( Styles::class )->enqueue_styles();
		}, 10, 0 );
	}
}
