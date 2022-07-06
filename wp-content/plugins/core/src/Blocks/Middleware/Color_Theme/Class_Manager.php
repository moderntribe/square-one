<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use Ds\Map;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;

/**
 * Retrieve a theme CSS class based on an acf-swatch hex code.
 */
class Class_Manager implements Appearance {

	protected Map $class_map;

	/**
	 * An sprintf() compatible string for the base CSS class that will
	 * have its color theme string added to it.
	 *
	 * @example 't-theme--%s' or 't-theme--%1$s'
	 */
	protected string $class_blueprint;

	public function __construct( Map $class_map, string $class_blueprint ) {
		$this->class_map       = $class_map;
		$this->class_blueprint = $class_blueprint;
	}

	/**
	 * Retrieve the mapped CSS class from a color hex code.
	 *
	 * @param string $hex e.g. #FFFFFF
	 *
	 * @return string
	 */
	public function get_class( string $hex ): string {
		$theme = $this->class_map->get( $hex, '' );

		return $theme ? $this->sanitize_class( $theme ) : '';
	}

	protected function sanitize_class( string $class ): string {
		return sanitize_html_class( sprintf( $this->class_blueprint, sanitize_title( $class ) ) );
	}

}
