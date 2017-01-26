<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\ElasticSearch\Config;
use Tribe\Project\ElasticSearch\Re_Index;
use Tribe\Project\ElasticSearch\Rest_Api;

/**
 * ElasticSearch_Provider
 *
 * @package Tribe\Project\Service_Providers
 */
class ElasticSearch_Provider  implements ServiceProviderInterface {
	public function register( Container $container ){
		$container[ 'elasticsearch.config' ] = function ( Container $container ){
			return new Config();
		};

		$container[ 'elasticsearch.rest_api' ] = function ( Container $container ){
			return new Rest_Api();
		};
		$container[ 'elasticsearch.re_index' ] = function ( Container $container ){
			return new Re_Index();
		};

		$this->hook( $container );
	}

	private function hook( Container $container ){
		$container[ 'service_loader' ]->enqueue( 'elasticsearch.config', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'elasticsearch.rest_api', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'elasticsearch.re_index', 'init' );
	}
}