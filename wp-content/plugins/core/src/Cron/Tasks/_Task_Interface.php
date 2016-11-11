<?php

namespace Tribe\Project\Cron\Tasks;

use Tribe\Project\Cron\Scheduled;

/**
 * Interface _Task_Interface
 *
 * Each task should implement this interface.
 * It contains the very minimum a task class should have.
 *
 * It is recommended to use the _Task trait along with this
 * but it not required.
 *
 * @package Tribe\Project\Cron\Tasks
 */
interface _Task_Interface {

	/**
	 * Run by Scheduled
	 *
	 * @see Scheduled
	 *
	 * @return bool
	 */
	public function run_task();


	/**
	 * Use to schedule this task
	 * Add the class name to registered tasks
	 *
	 * @see Scheduled
	 *
	 * @return bool
	 */
	public function schedule();


	/**
	 * Is the task complete or is it just
	 * running one round of a batch and
	 * requires more rounds
	 *
	 * @return bool
	 */
	public function is_complete();

	/**
	 *
	 * @static
	 *
	 * @return self
	 */
	public static function instance();

}