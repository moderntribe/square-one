<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Post_Meta\Meta_Repository;
use Tribe\Project\Post_Meta\Sample_Post_Meta;
use Tribe\Project\Post_Types\Sample\Sample;

/**
 * Class Post_Meta_Service_Provider
 * @package Tribe\Project\Service_Providers\Post_Meta
 *
 * Register all post meta classes here.
 */
class Post_Meta_Service_Provider implements ServiceProviderInterface {

	/**
	 * @param Container $container
	 */
	public function register( Container $container ) {

		$this->post_meta( $container );

		/**
		 * @param Container $container
		 *
		 * @return Meta_Repository
		 *
		 * Add $container[ 'post_meta.NAME' ] to the Meta_Repository array parameter for ALL post meta classes
		 */
		$container['post_meta.collection_repo'] = function ( Container $container ) {
			return new Meta_Repository( [
				$container['post_meta.sample_post_meta'],
				// $container[ 'post_meta.another_post_meta' ],
			] );
		};

		/**
		 * Hook all registered post meta in collection repo
		 */
		add_action( 'plugins_loaded', function () use ( $container ) {
			$container['post_meta.collection_repo']->hook();
		}, 1000, 0 );

	}

	/**
	 * @param Container $container
	 *
	 * Register each post meta class using post type classes in array as parameter
	 */
	protected function post_meta( Container $container ) {

		$container['post_meta.sample_post_meta'] = function () {
			return new Sample_Post_Meta( [
				Sample::NAME,
			] );
		};

		//$container[ 'post_meta.another_post_meta' ] = function () { ...

	}

}