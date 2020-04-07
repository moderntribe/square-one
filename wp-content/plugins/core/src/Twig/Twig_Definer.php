<?php
declare( strict_types=1 );

namespace Tribe\Project\Twig;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Twig\Cache\FilesystemCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

class Twig_Definer implements Definer_Interface {
	public const OPTIONS = 'twig.options';

	public function define(): array {
		return [
			Twig_Cache::class => DI\autowire()->constructor( defined( 'TWIG_CACHE_DIR' ) && TWIG_CACHE_DIR ? TWIG_CACHE_DIR : WP_CONTENT_DIR . '/cache/twig/', FilesystemCache::FORCE_BYTECODE_INVALIDATION ),

			self::OPTIONS => function ( ContainerInterface $container ) {
				return apply_filters( 'tribe/project/twig/options', [
					'debug'       => WP_DEBUG,
					'cache'       => $container->get( Twig_Cache::class ),
					'autoescape'  => false,
					'auto_reload' => true,
				] );
			},

			LoaderInterface::class => function () {
				$stylesheet_path = get_stylesheet_directory();
				$template_path   = get_template_directory();
				$loader          = new FilesystemLoader( [ $stylesheet_path ] );
				if ( $template_path !== $stylesheet_path ) {
					$loader->addPath( $template_path );
				}

				return $loader;
			},

			Environment::class => DI\autowire()
				->constructorParameter( 'options', DI\get( self::OPTIONS ) )
				->method( 'addExtension', DI\get( Extension::class ) ),
		];
	}

}
