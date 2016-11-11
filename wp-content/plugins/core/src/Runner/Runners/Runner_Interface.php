<?php
/**
 * Runner_Interface.php
 *
 * @author  Mat
 * @since   11/11/2016
 *
 * @package Tribe\Project\Runner_Interface\Runners *
 */

namespace Tribe\Project\Runner\Runners;

interface Runner_Interface {

	public function init();

	public function get_next_run_time();

	public function get_last_run_time();

	public function run_tasks();
}