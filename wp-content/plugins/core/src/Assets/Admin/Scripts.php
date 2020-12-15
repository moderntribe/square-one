<?php

namespace Tribe\Project\Assets\Admin;

class Scripts {
	/**
	 * @var JS_Config
	 */
	private $config;
	/**
	 * @var JS_Localization
	 */
	private $localization;
	/**
	 * @var Admin_Build_Parser
	 */
	private $build_parser;

	public function __construct( Admin_Build_Parser $build_parser, JS_Config $config, JS_Localization $localization ) {
		$this->build_parser = $build_parser;
		$this->config = $config;
		$this->localization = $localization;
	}


	/**
	 * @return void
	 * @action admin_init
	 */
	public function register_scripts(): void {
		// If constant is true, set version to current timestamp, forcing cache invalidation on every page load
		$timestamp = defined( 'ASSET_VERSION_TIMESTAMP' ) && ASSET_VERSION_TIMESTAMP === true ? time() : null;
		foreach ( $this->build_parser->get_scripts() as $handle => $asset ) {
			wp_register_script( $handle, $asset['uri'], $asset['dependencies'], $timestamp ?? $asset['version'], true );
		}
	}

	/**
	 * @action admin_enqueue_scripts
	 */
	public function enqueue_scripts(): void {

		$handles = $this->build_parser->get_script_handles();
		foreach ( $handles as $handle ) {
			wp_enqueue_script( $handle );
		}

		$this->localize_scripts( reset( $handles ) );
	}

	private function localize_scripts( string $handle ): void {
		wp_localize_script( $handle, 'modern_tribe_admin_i18n', $this->localization->get_data() );
		wp_localize_script( $handle, 'modern_tribe_admin_config', $this->config->get_data() );
	}
}
