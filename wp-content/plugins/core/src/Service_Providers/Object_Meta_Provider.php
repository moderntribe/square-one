<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Object_Meta\Meta_Repository;
use Tribe\Project\Object_Meta\Example;
use Tribe\Project\Object_Meta\Place;
use Tribe\Project\Post_Types;
use Tribe\Project\Settings;
use Tribe\Project\Taxonomies;

class Object_Meta_Provider implements ServiceProviderInterface {
	const REPO    = 'object_meta.collection_repo';
	const EXAMPLE = 'object_meta.example';
	const PLACE   = 'ojbect_meta.place';

	private $keys = [
		self::EXAMPLE,
		self::PLACE,
	];

	public function register( Container $container ) {
		$this->example( $container );
		$this->posts( $container );

		$container[ self::REPO ] = function ( Container $container ) {
			$meta_repo = array_map( function ( $key ) use ( $container ) {
				return $container[ $key ];
			}, $this->keys );

			return new Meta_Repository( $meta_repo );
		};

		add_action( 'init', function () use ( $container ) {
			$container[ self::REPO ]->hook();
		} );

		add_action( 'acf/init', function () use ( $container ) {
			array_walk( $this->keys, function ( $key ) use ( $container ) {
				$container[ $key ]->register_group();
			} );
		}, 10, 0 );
	}

	private function example( Container $container ) {
		$container[ self::EXAMPLE ] = function ( Container $container ) {
			return new Example( [
				'post_types'     => [ Post_Types\Page\Page::NAME, Post_Types\Post\Post::NAME ],
				'taxonomies'     => [ Taxonomies\Category\Category::NAME ],
				'settings_pages' => [ Settings\General::instance()->get_slug() ],
				'users'          => true,
			] );
		};
	}

	private function posts ( Container $container ) {
		$container[ self::PLACE ] = function ( Container $container ) {
			return new Place( [
				'post_types'     => [ Post_Types\Place\Place::NAME ],
				'taxonomies'     => [ Taxonomies\Category\Category::NAME ],
				'settings_pages' => [ Settings\General::instance()->get_slug() ],
				'users'          => true,
			]);
		};
	}
}