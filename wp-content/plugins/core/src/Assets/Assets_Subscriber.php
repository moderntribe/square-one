<?php declare(strict_types=1);

namespace Tribe\Project\Assets;

use Tribe\Libs\Container\Abstract_Subscriber;
use \WP_Theme;

class Assets_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		$theme = wp_get_theme();

		if ( $theme instanceof WP_Theme && $theme->exists() ) {
			$checks = [
				$theme->get_stylesheet(),
				$theme->parent() ? $theme->parent()->get_stylesheet() : null,
			];

			$checks = array_filter( $checks );

			// Bail if we're not using the core theme.
			if ( ! in_array( 'core', $checks, true ) ) {
				return;
			}
		}

		$this->theme_resources();
		$this->admin_resources();
		$this->login_resources();
	}


	private function theme_resources(): void {
		add_action( 'template_redirect', function (): void {
			$this->container->get( Theme\Scripts::class )->register_scripts();
			$this->container->get( Theme\Styles::class )->register_styles();
		}, 10, 0 );

		add_action( 'wp_enqueue_scripts', function (): void {
			$this->container->get( Theme\Scripts::class )->enqueue_scripts();
			$this->container->get( Theme\Styles::class )->enqueue_styles();
			$this->container->get( Theme\Styles::class )->dequeue_block_styles();
		}, 10, 0 );

		add_action( 'wp_head', function (): void {
			$this->container->get( Theme\Scripts::class )->maybe_inject_bugsnag();
		}, 0, 0 );
		add_action( 'wp_head', function (): void {
			$this->container->get( Theme\Scripts::class )->set_preloading_tags();
		}, 10, 0 );
		add_action( 'wp_footer', function (): void {
			$this->container->get( Theme\Scripts::class )->add_early_polyfills();
		}, 10, 0 );
	}

	private function admin_resources(): void {
		add_action( 'admin_init', function (): void {
			$this->container->get( Admin\Scripts::class )->register_scripts();
			$this->container->get( Admin\Styles::class )->register_styles();
			$this->container->get( Admin\Styles::class )->remove_editor_style_reset();
		}, 10, 0 );


		add_action( 'admin_enqueue_scripts', function (): void {
			$this->container->get( Admin\Scripts::class )->enqueue_scripts();
			$this->container->get( Admin\Styles::class )->enqueue_styles();
		}, 10, 0 );
	}

	private function login_resources(): void {
		add_action( 'login_init', function (): void {
			$this->container->get( Admin\Styles::class )->register_styles();
		}, 10, 0 );

		add_action( 'login_enqueue_scripts', function (): void {
			$this->container->get( Admin\Styles::class )->enqueue_login_styles();
		}, 10, 0 );
	}

}
