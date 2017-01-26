<?php
namespace Tribe\Project\Runner\Tasks;

use Tribe\Project\Runner\Scheduled;

/**
 * Task
 *
 * Shared methods for all tasks
 *
 * @package Tribe\Project\Runner_Interface\Tasks *
 */
trait Task {

	public function schedule(){
		$scheduled = Scheduled::instance();
		$scheduled->schedule_task( $this );
	}
}