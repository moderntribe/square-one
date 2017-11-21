<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Queues\Backends\Mysql;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\DefaultQueue;
use Tribe\Project\Queues\Tasks\Null_Task;

class Queues_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['queues.backend.wp_cache'] = function(){
			return new WP_Cache();
		};

		$container['queues.backend.mysql'] = function() {
			return new Mysql();
		};

		$container['queues.DefaultQueue'] = function ( $container ) {

			// We probably want a constant based conditional/switch here
			// to allow easy backend change in different environments
			$backend = $container['queues.backend.wp_cache'];
			$backend = $container['queues.backend.mysql'];

			return new DefaultQueue( $backend );
		};

		add_action( 'plugins_loaded', function () use ($container) {
			$container['queues.DefaultQueue'];

			// Add the item to the queue.
			$container['queues.DefaultQueue']->dispatch( Null_Task::class, [ 'fake' => 'task' ], 10 );

			// Pull the queue item.
			$job = $container['queues.DefaultQueue']->reserve();

			// Verify we can dispatch the task.
			$task_class = $job->get_task_handler();
			if ( ! class_exists( $task_class ) ) {
				return;
			}

			// Process the task.
			$task = new $task_class();
			if ( $task->handle( $job->get_args() ) ) {
				// Acknowledge.
				$container['queues.DefaultQueue']->ack( $job->get_job_id() );
			} else {
				$container['queues.DefaultQueue']->nack( $job->get_job_id() );
			}

		} );
	}
}