<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Theme;

class JS_Config {

	/**
	 * @var array<string, bool|string>
	 */
	private array $data;

	public function get_data(): array {
		if ( ! isset( $this->data ) ) {
			$this->data = [
				'images_url'   => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme',
				'template_url' => trailingslashit( get_template_directory_uri() ),
				'script_debug' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true,
				'hmr_dev'      => defined( 'HMR_DEV' ) && HMR_DEV === true,
			];

			$this->data = (array) apply_filters( 'core_js_config', $this->data );
		}

		return $this->data;
	}

}
