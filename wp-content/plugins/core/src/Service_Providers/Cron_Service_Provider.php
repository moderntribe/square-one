<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Cron\Scheduled;
use Tribe\Project\Cron\Scheduled_Cron;
use Tribe\Project\Cron\Tasks\Email;
use Tribe\Project\Cron\Tasks\Re_Index;

/**
 * Cron_Service_Provider
 *
 * @author  Mat Lipe
 * @since   10/3/2016
 *
 * @package Tribe\Project\Service_Providers
 */
class Cron_Service_Provider implements ServiceProviderInterface {
	public function register( Container $container ){
		$container[ 'cron.scheduled' ] = function ( Container $container ){
			return new Scheduled();
		};

		$container[ 'cron.scheduled_cron' ] = function ( Container $container ){
			return new Scheduled_Cron();
		};

		$container[ 'cron.tasks.email' ] = function ( Container $container ){
			return new Email();
		};

		$container[ 'cron.tasks.re_index' ] = function ( Container $container ){
			return new Re_Index();
		};

		$this->hook( $container );
	}


	private function hook( Container $container ){
		$container[ 'service_loader' ]->enqueue( 'cron.scheduled_cron', 'init' );
	}
}