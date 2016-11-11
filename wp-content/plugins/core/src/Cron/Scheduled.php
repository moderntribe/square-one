<?php
namespace Tribe\Project\Cron;

use Tribe\Project\Cron\Tasks\_Task_Interface;

/**
 * Scheduled
 *
 * Stores tasks and runs when when something tell it to do
 *
 * To add a new task send the name of the class to $this->schedule_task()
 * Run all stored tasks by calling $this->run_tasks()
 *
 * By default this is called by the Scheduled_Cron WP_Cron
 * @see Scheduled_Cron::run_cron()
 *
 * @package Tribe\Project\Cron
 *
 */
class Scheduled {
	const OPTION = 'core_scheduled_tasks';
	const TIME_LIMIT = 60000; //10 minutes


	public function run_tasks(){
		set_time_limit( self::TIME_LIMIT );

		$tasks = $this->get_existing_tasks();
		foreach( $tasks as $k => $_class ){
			/** @var _Task_Interface $_class */
			$task = $_class::instance();
			if( !$task->run_task() ){
				throw( new \Exception( sprintf( __( "Schedule task %s did not complete", 'tribe' ), $_class ) ) );
			}
			if( $task->is_complete() ){
				unset( $tasks[ $k ] );
				//clear as we go in case of timeout
				update_option( self::OPTION, $tasks );
			}
		}

		return true;
	}


	public function schedule_task( $task_class ){
		$tasks = $this->get_existing_tasks();
		if( !in_array( $task_class, $tasks ) ){
			$tasks[] = $task_class;
			update_option( self::OPTION, $tasks );
		}

	}


	private function get_existing_tasks(){
		return get_option( self::OPTION, [] );
	}


	/**
	 *
	 * @static
	 *
	 * @return Scheduled
	 */
	public static function instance(){
		return tribe_project()->container()[ 'cron.scheduled' ];
	}
}