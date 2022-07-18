<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Settings;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Object_Meta_Definer implements Definer_Interface {

	public function define(): array {
		return [
			// add our meta groups to the global array
			\Tribe\Libs\Object_Meta\Object_Meta_Definer::GROUPS => DI\add( [
				DI\get( Analytics_Settings::class ),
				DI\get( Social_Settings::class ),
				DI\get( Post_Archive_Settings::class ),
				DI\get( Post_Archive_Featured_Settings::class ),
				DI\get( Taxonomy_Archive_Settings::class ),
			] ),

			// add analytics settings to the general settings screen
			Analytics_Settings::class                           => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Settings\Theme_Options::class )->get_slug() ],
				] ),

			// add social settings to the general settings screen
			Social_Settings::class                              => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Settings\Theme_Options::class )->get_slug() ],
				] ),

			Post_Archive_Featured_Settings::class               => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Settings\Post_Settings::class )->get_slug() ],
				] ),

			Post_Archive_Settings::class                        => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Settings\Post_Settings::class )->get_slug() ],
				] ),

			Taxonomy_Archive_Settings::class                    => DI\autowire()
				->constructorParameter( 'object_types', static fn() => [
					'taxonomies' => [
						Category::NAME,
						Post_Tag::NAME,
					],
				] ),
		];
	}

}
