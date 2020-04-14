<?php


namespace Tribe\Project\Theme\Resources;


class JS_Config {

	private $data;

	public function get_data() {
		if ( !isset( $this->data ) ) {
			$this->data = [
				'images_url'                 => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme',
				'template_url'               => trailingslashit( get_template_directory_uri() ),
				'script_debug'               => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true,
				'hmr_dev'                    => defined( 'HMR_DEV' ) && HMR_DEV === true,
				'block_theme_service_worker' => defined( 'BLOCK_THEME_SERVICE_WORKER' ) && BLOCK_THEME_SERVICE_WORKER === true,
			];
			$this->data = apply_filters( 'core_js_config', $this->data );
		}

		return $this->data;
	}
}
