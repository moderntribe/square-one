<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use DI;
use Ds\Map;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;

class Color_Theme_Definer implements Definer_Interface {

	public const THEME_MAP           = 'color_theme_definer.theme_map';
	public const CSS_CLASS_BLUEPRINT = 'color_theme_definer.css_class_blueprint';

	public function define(): array {
		return [
			/**
			 * Define the hex code => CSS class name relationship.
			 *
			 * @var array<string, string>
			 *
			 * @see \Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance
			 */
			self::THEME_MAP           => DI\add( [
				Appearance::THEME_WHITE      => 'white',
				Appearance::THEME_LIGHT_GRAY => 'light-gray',
			] ),

			// The CSS color theme class blueprint, e.g. "t-theme--white"
			self::CSS_CLASS_BLUEPRINT => 't-theme--%s',

			Class_Manager::class      => DI\autowire()
				->constructorParameter(
					'class_map',
					static fn ( ContainerInterface $c ) => new Map( $c->get( self::THEME_MAP ) )
				)->constructorParameter(
					'class_blueprint',
					static fn ( ContainerInterface $c ) => $c->get( self::CSS_CLASS_BLUEPRINT )
				),

			Color_Theme_Field::class  => static fn ( ContainerInterface $c ) => $c->get( Color_Theme_Field_Manager::class ),
		];
	}

}
