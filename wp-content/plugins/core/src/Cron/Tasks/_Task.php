<?php
namespace Tribe\Project\Cron\Tasks;

use Tribe\Project\Cron\Scheduled;

/**
 * Task
 *
 * Shared methods for all tasks
 *
 * @package Tribe\Project\Cron\Tasks *
 */
trait _Task {

	public function schedule(){
		$scheduled = Scheduled::instance();
		$scheduled->schedule_task( get_called_class() );
	}
}