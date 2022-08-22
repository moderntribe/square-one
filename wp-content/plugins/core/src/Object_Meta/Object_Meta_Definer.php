<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Settings;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Object_Meta_Definer implements Definer_Interface {

	public const THEME_OPTIONS_COLLECTION = 'object_meta_definer.theme_options.fields';

	public function define(): array {
		return [
			// add our meta groups to the global array
			\Tribe\Libs\Object_Meta\Object_Meta_Definer::GROUPS => DI\add( [
				DI\get( Theme_Setting::class ),
				DI\get( Post_Archive_Settings::class ),
				DI\get( Post_Archive_Featured_Settings::class ),
				DI\get( Taxonomy_Archive_Settings::class ),
			] ),

			/**
			 * Define general settings theme options.
			 *
			 * @var \Tribe\Project\Object_Meta\Contracts\Abstract_Tab[]
			 */
			self::THEME_OPTIONS_COLLECTION                      => DI\add( [
				DI\get( Analytics_Settings::class ),
				DI\get( Footer_Settings::class ),
				DI\get( Social_Settings::class ),
			] ),

			Theme_Setting::class                                => DI\autowire()
				->constructorParameter( 'object_types', static fn( ContainerInterface $c ) => [
					'settings_pages' => [ $c->get( Settings\Theme_Options::class )->get_slug() ],
				] )
				->constructorParameter(
					'settings',
					static fn( ContainerInterface $c ) => $c->get( self::THEME_OPTIONS_COLLECTION ),
				),

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
