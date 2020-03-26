<?php
declare( strict_types=1 );

use Psr\Container\ContainerInterface;
use Tribe\Project\Twig\Extension;
use Tribe\Project\Twig\Twig_Cache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

return [
	Twig_Cache::class      => DI\autowire()->constructor( WP_CONTENT_DIR . '/cache/twig/' ),
	'twig.options'         => function ( ContainerInterface $container) {
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
	Environment::class     => DI\autowire()
		->constructorParameter( 'options', DI\get( 'twig.options' ) )
		->method( 'addExtension', DI\get( Extension::class ) ),
];

