<?php
/**
 * Created by PhpStorm.
 * User: garykovar
 * Date: 9/27/17
 * Time: 3:19 PM
 */

namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Places_API\Google_API;
use Tribe\Project\Post_Types\Place\Place;

class Place_API_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$this->register_google_api_settings( $container );
	}

	private function register_google_api_settings( $container ) {
		$container[ 'places_api.google_api' ] = function( Container $container ) {
			return new Google_API();
		};

		add_action( 'acf/save_post', function ( $post_id ) use ( $container ) {
			$container[ 'places_api.google_api' ]->get_place( $post_id );
		}, 15, 1 );
	}

}