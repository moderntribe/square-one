<?php
declare( strict_types=1 );

namespace Tribe\Project\Object_Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Object_Meta\Meta_Repository;
use Tribe\Project\Post_Types;
use Tribe\Project\Settings;
use Tribe\Project\Taxonomies;

class Object_Meta_Definer implements Definer_Interface {
	public const GROUPS = 'meta.groups';

	public function define(): array {
		return [
			self::GROUPS => [
				DI\get( Example::class ),
				DI\get( Analytics_Settings::class ),
				DI\get( Social_Settings::class ),
			],

			Meta_Repository::class => DI\create()
				->constructor( DI\get( self::GROUPS ) ),

			Example::class => function ( ContainerInterface $container ) {
				return new Example( [
					'post_types'     => [ Post_Types\Page\Page::NAME, Post_Types\Post\Post::NAME ],
					'taxonomies'     => [ Taxonomies\Category\Category::NAME ],
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
					'users'          => true,
				] );
			},

			Analytics_Settings::class => function ( ContainerInterface $container ) {
				return new Analytics_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			Social_Settings::class => function ( ContainerInterface $container ) {
				return new Social_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},
		];
	}
}
