<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Runner\Scheduled;
use Tribe\Project\Runner\Runners\Cron;
use Tribe\Project\Runner\Tasks\Re_Index;

/**
 * Runner_Service_Provider
 *
 * @author  Mat Lipe
 * @since   10/3/2016
 *
 * @package Tribe\Project\Service_Providers
 */
class Runner_Service_Provider implements ServiceProviderInterface {
	public function register( Container $container ){
		$container[ 'runner.scheduled' ] = function ( Container $container ){
			return new Scheduled();
		};

		$container[ 'runner.runners.cron' ] = function ( Container $container ){
			return new Cron();
		};

		$container[ 'runner.tasks.re_index' ] = function ( Container $container ){
			return new Re_Index();
		};

		$this->hook( $container );
	}


	private function hook( Container $container ){
		if( defined( 'TASK_RUNNER' ) ){
			$container[ 'service_loader' ]->enqueue( TASK_RUNNER, 'init' );
		} else {
			$container[ 'service_loader' ]->enqueue( 'runner.runners.cron', 'init' );

		}
	}
}