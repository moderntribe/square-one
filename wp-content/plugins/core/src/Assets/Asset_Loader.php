<?php


namespace Tribe\Project\Assets;


class Asset_Loader {
	private $base_dir = '';

	public function __construct( $base_dir ) {
		$this->base_dir = trailingslashit( $base_dir );
	}

	public function register_and_enqueue_script( $handle, $relative_path, $dependencies = [], $version = '', $in_footer = false ) {
		$this->register_script(  $handle, $relative_path, $dependencies, $version, $in_footer );
		$this->enqueue_script( $handle );
	}

	public function register_script( $handle, $relative_path, $dependencies = [], $version = '', $in_footer = false ) {
		$path = $this->get_url( $relative_path );
		$version = $version ?: tribe_get_version();
		wp_register_script( $handle, $path, $dependencies, $version, $in_footer );
	}

	public function enqueue_script( $handle ) {
		wp_enqueue_script( $handle );
	}

	public function register_and_enqueue_stylesheet( $handle, $relative_path, $dependencies = [ ], $version = '', $media = 'all' ) {
		$this->register_stylesheet( $handle, $relative_path, $dependencies, $version, $media );
		$this->enqueue_stylesheet( $handle );
	}

	public function register_stylesheet( $handle, $relative_path, $dependencies = [], $version = '', $media = 'all' ) {
		$path = $this->get_url( $relative_path );
		$version = $version ?: tribe_get_version();
		wp_register_style( $handle, $path, $dependencies, $version, $media );
	}

	public function enqueue_stylesheet( $handle ) {
		wp_enqueue_style( $handle );
	}

	public function get_url( $relative_path ) {
		return plugins_url( $relative_path, $this->base_dir . 'arbitrary.string' );
	}

	public function get_path( $relative_path ) {
		return $this->base_dir . $relative_path;
	}

	public function localize_script( $handle, $localization_object_name, $localization_data ) {
		return wp_localize_script( $handle, $localization_object_name, $localization_data );
	}
}