<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;

class Color_Theme_Definer implements Definer_Interface {

	public const CSS_CLASS_BLUEPRINT = 'color_theme_definer.css_class_blueprint';

	public function define(): array {
		return [
			// The CSS color theme class blueprint, e.g. "t-theme--white"
			self::CSS_CLASS_BLUEPRINT => 't-theme--%s',

			Class_Manager::class      => DI\autowire()
				->constructorParameter(
					'class_blueprint',
					static fn ( ContainerInterface $c ) => $c->get( self::CSS_CLASS_BLUEPRINT )
				),

			Color_Theme_Field::class  => static fn ( ContainerInterface $c ) => $c->get( Color_Theme_Field_Manager::class ),
		];
	}

}
