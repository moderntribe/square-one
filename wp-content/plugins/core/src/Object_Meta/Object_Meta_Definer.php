<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Object_Meta\Appearance\Global_Appearance_Settings;
use Tribe\Project\Object_Meta\Appearance\Post_Appearance_Settings;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
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
				DI\get( Taxonomy_Archive_Settings::class ),
				DI\get( Global_Appearance_Settings::class ),
				DI\get( Post_Appearance_Settings::class ),
			] ),

			// add analytics settings to the general settings screen
			Analytics_Settings::class                           => static function ( ContainerInterface $container ) {
				return new Analytics_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			// add social settings to the general settings screen
			Social_Settings::class                              => static function ( ContainerInterface $container ) {
				return new Social_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			Post_Archive_Settings::class => static function ( ContainerInterface $container ) {
				return new Post_Archive_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			Taxonomy_Archive_Settings::class => static function (): Taxonomy_Archive_Settings {
				return new Taxonomy_Archive_Settings( [
					'taxonomies' => [ Category::NAME, Post_Tag::NAME ],
				] );
			},

			// add global appearance settings
			Global_Appearance_Settings::class                   => static function ( ContainerInterface $container ) {
				return new Global_Appearance_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			// add post level appearance settings
			Post_Appearance_Settings::class                     => static function () {
				return new Post_Appearance_Settings( [
					'post_types' => [
						Post::NAME,
						Page::NAME,
					],
				] );
			},
		];
	}
}
