<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Post_Meta\Meta_Repository;
use Tribe\Project\Post_Types;
use Tribe\Project\Taxonomies;
use Tribe\Project\Settings;

class Object_Meta_Provider implements ServiceProviderInterface {

	protected $field_groups = [];

	public function __construct() {
		$this->field_groups = [
			'Example' => [
				'post_types'     => [ Post_Types\Page\Page::NAME, Post_Types\Post\Post::NAME ],
				'taxonomies'     => [ Taxonomies\Category\Category::NAME ],
				'settings_pages' => [ tribe_project()->container()['settings.general']->get_slug() ],
				'users'          => true,
			],
		];
	}

	public function register( Container $container ) {

		$meta_repo = [];

		foreach ( $this->field_groups as $group => $object_types ) {

			$key                  = 'object_meta.' . strtolower( $group );
			$object_meta_class_name = '\\Tribe\\Project\\Object_Meta\\' . $group;

			$container[ $key ] = function ( $container ) use ( $object_meta_class_name, $object_types ) {
				return new $object_meta_class_name( $object_types );
			};

			$meta_repo[] = $container[ $key ];

			add_action( 'acf/init', function() use ( $container, $key ) {
				$container[ $key ]->register_group();
			}, 10, 0 );
		}

		$container['object_meta.collection_repo'] = function ( Container $container ) use ( $meta_repo ) {
			return new Meta_Repository( $meta_repo );
		};

		add_action( 'init', function() use ( $container ) {
			$container['object_meta.collection_repo']->hook();
		});
	}
}