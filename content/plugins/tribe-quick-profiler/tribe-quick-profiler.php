<?php
/**
 * Plugin Name: Tribe Quick Profiler
 * Provides an extremely light and efficient way to track what's running slow and heavy in your WordPress install.
 *
 * Installation:
 *
 * 1. Place this file in your wp-content/mu-plugins folder (create the folder if it doesn't already exist.)
 * 2. Add the following definitions to your wp-config.php file with desired settings.
 * 3. View your site and watch the logs to see what's slow.
 *
 * // Sampling rate
 * // This determines the odds that this plugin will run.
 * // Set this very low if you are testing on a high traffic site.
 * // Or set it to '1' if you want every page load to be profiled.
 *
 * define('TRIBE_PROFILE_SAMPLE_RATE', '0.5');
 *
 *
 * // Time Threshold (milliseconds)
 * // Determine the minimum amount of time increase that should trigger logging in ms.
 * // If the time spent between filters exceeds this amount, it will be logged.
 *
 * define('TRIBE_PROFILE_TIME_THRESHOLD', '10');
 *
 *
 * // Memory Threshold (kilobytes)
 * // Determine the minimum amount of memory increase that should trigger logging in kB.
 * // If the memory consumed between filters exceeds this amount, it will be logged.
 *
 * define('TRIBE_PROFILE_MEMORY_THRESHOLD', '1024');
 *
 *
 * // Log File
 * // If this is not set, then logging will be directed to the standard php error log.
 * // If set to '1' or true, then logging will be directed to wp-content/tribe_profile.log
 * // If set to a full path, then logging will be directed to the specified file.
 *
 * define('TRIBE_PROFILE_LOG_FILE', true);
 *
 *
 * // Display args
 * // If this is set to '1' or true then the first argument passed to the filter will also be included in the log dump.
 *
 * define('TRIBE_PROFILE_DISPLAY_ARGS', true);
 *
 * TODO
 * * Perhaps we should dump the results on shutdown and build a profiling viewer.
 * * This plugin does not currently assess cumulative effects. The cumulative effect of running a single hook 10000 times could be useful.
 * * It might be useful to add a log cycling function so that we avoid massive log files.
 * * Sometimes memory actually drops substantially. Is it worth logging that too?
 */

if ( !class_exists('Tribe_Quick_Profiler') ) {

	class Tribe_Quick_Profiler {

		private $log = array();

		private $key;

		// 0 = off, 1 = always.
		private $sampling_rate = 1;

		// minimum time in milliseconds for reporting.
		private $time_threshold = 10;

		// minimum memory in kb for reporting.
		private $mem_threshold = 1024;

		// log file path.
		private $log_file;

		// include the first arg passed to the filter in the log.
		private $display_args = false;

		private $time = array();
		private $timediff = array();
		private $memory = array();
		private $memorydiff = array();

		public function __construct() {

			if ( defined('TRIBE_PROFILE_SAMPLE_RATE') ) $this->sampling_rate = TRIBE_PROFILE_SAMPLE_RATE;

			if ( !$this->sampling_rate ) return; // sample rate = 0 so turn this off.

			$rand = rand( 0, 999 );
			if ( $this->sampling_rate < 1 && $rand < $this->sampling_rate * 1000 ) return; // sample rate tested and load is skipped.

			$this->key = str_pad($rand,4,"0",STR_PAD_LEFT); // profiled load.

			if ( defined('TRIBE_PROFILE_TIME_THRESHOLD') ) $this->time_threshold = TRIBE_PROFILE_TIME_THRESHOLD;
			if ( defined('TRIBE_PROFILE_MEMORY_THRESHOLD') ) $this->mem_threshold = TRIBE_PROFILE_MEMORY_THRESHOLD;
			if ( defined('TRIBE_PROFILE_LOG_FILE') ) $this->log_file = ( TRIBE_PROFILE_LOG_FILE == '1' || TRIBE_PROFILE_LOG_FILE == true ) ? WP_CONTENT_DIR.'/tribe_profile.log' : TRIBE_PROFILE_LOG_FILE;
			if ( defined('TRIBE_PROFILE_DISPLAY_ARGS') ) $this->display_args = ( TRIBE_PROFILE_DISPLAY_ARGS == '1' || TRIBE_PROFILE_DISPLAY_ARGS == true ) ? true : false;

			$this->log = new stdClass();
			$this->log->url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			$this->get_time( 'global' );
			$this->get_memory( 'global' );

			$this->log->time_start = $this->time['global'];
			$this->log->memory_start = $this->memory['global'];
			$this->log->key = $this->key;
			$this->log->data = array();

			add_action( 'all', array( $this, 'profile_time' ), 0, 2 );
			add_action( 'all', array( $this, 'cumulative_logging' ), 0, 1 );
			add_action( 'shutdown', array( $this, 'log_dump' ) ,9999);
		}

		private function get_time( $key = 'default' ) {
			// Calculate time diff
			global $timestart;
			$timeend = microtime( true );
			$time = round( ($timeend - $timestart) * 1000 );
			$this->timediff[$key] = isset($this->time[$key]) ? $time - $this->time[$key] : 0;
			$this->time[$key] = $time;
		}

		private function get_memory( $key = 'default' ) {
			// Calculate memory diff
			$memory = round( memory_get_usage(  )/1024 );
			$this->memorydiff[$key] = isset($this->memory[$key]) ? $memory - $this->memory[$key] : 0;
			$this->memory[$key] = $memory;
		}

		public function cumulative_logging( $hook ) {
			$this->get_time( 'cumulative_logging' );
			$this->get_memory( 'cumulative_logging' );
		}

		public function profile_time( $hook ) {

			/*$all_args = func_get_args();
			$this->profiler_log( array( $hook, print_r($all_args,true) ) );
			return;*/

			$this->get_time( 'profile_time' );
			$this->get_memory( 'profile_time' );

			static $previous_hook;

			if ( !empty($previous_hook) && ( $this->timediff['profile_time'] > $this->time_threshold || $this->memorydiff['profile_time'] > $this->mem_threshold ) ) {
				$this->logentry = new stdClass();
				$this->logentry->hook = $previous_hook;
				$this->logentry->time = $this->time['profile_time'];
				$this->logentry->timediff = $this->timediff['profile_time'];
				$this->logentry->memory = $this->memory['profile_time'];
				$this->logentry->memorydiff = $this->memorydiff['profile_time'];
				if ($this->display_args) {
					$all_args = func_get_args();
					if ( isset($all_args[1]) ) {
						if ( is_object($all_args[1]) || is_array($all_args[1]) ) {
							$this->logentry->arg = print_r($all_args[1],true);
						} else {
							$this->logentry->arg = $all_args[1];
						}
					} else {
						$this->logentry->arg = '';
					}
				}
				$this->log->data[] = $this->logentry;
				//global $wp_filter;
				//if (isset($wp_filter[$previous_hook])) error_log( __FUNCTION__ . ' : $wp_filter[$previous_hook] = ' . print_r( $wp_filter[$previous_hook], TRUE ) );
			}
			$previous_hook = $hook;
		}

		public function log_dump() {

			$this->get_time( 'global' );
			$this->get_memory( 'global' );

			$this->log->time_end = $this->time['global'];
			$this->log->time_diff = $this->timediff['global'];
			$this->log->memory_end = $this->memory['global'];
			$this->log->memory_diff = $this->memorydiff['global'];

			if ( !empty( $this->log->data ) ) {

				$this->profiler_log( array() );
				$this->profiler_log( array( $this->log->url ) );
				$this->profiler_log( array(
					sprintf( 'Profile Time: %s sec', round( $this->log->time_diff/1000, 2) ),
					sprintf( 'Profile Memory: %s MB', round( $this->log->memory_diff/1024, 2 ) ),
				) );
				$headers = array( 'TIME (ms)', 'DELTA', 'MEM (kB)', 'DELTA', 'FILTER' );
				if ($this->display_args) $headers[] = 'ARG';
				$this->profiler_log( $headers );

				foreach( $this->log->data as $row ) {
					$row_output = array(
						$row->time,
						$row->timediff,
						$row->memory,
						$row->memorydiff,
						$row->hook,
					);
					if ($this->display_args) $row_output[] = $row->arg;
					$this->profiler_log( $row_output );
				}
			}

		}

		/**
		 * Log the messages to a custom error log. Defaults to wp-content/tribe_profile.log.
		 * @param array $columns
		 */
		function profiler_log( $columns = array() ) {
			if ( !empty( $this->log_file ) ) {
				error_log( join( '	', $columns )."\n", 3, $this->log_file );
			} else {
				error_log( join( '	', $columns ) );
			}
		}

		/**
		 * Use this function in wp-includes/plugin.php around line 403 where it says:
		 * call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));
		 * Add this function on the same line directly after the semicolon (but don't leave it there for long).
		 *
		 * Example:
		 * tribe_function_dump( 'init', $tag, $the_['function'], $args );
		 *
		 * @param $hook_to_dump the hook we're looking for. for example: 'init'.
		 * @param $hook_now_processing the hook now processing. Use $tag.
		 * @param $function_now_processing the function now processing. Use $the_['function'].
		 * @param $args array of optional args passed to the function. Use $args
		 */
		public function function_dump( $hooks_to_dump, $hook_now_processing, $function_now_processing, $args=array() ) {

			if ( !is_array($hooks_to_dump) ) $hooks_to_dump = array($hooks_to_dump);

			global $timestart;

			static $last_function_dump;

			// use the raw vars instead of timer_stop() to avoid ln10 hooks
			$timeend = microtime( true );
			$time = round( ($timeend - $timestart) * 1000 );
			$diff = (isset($last_function_dump['time'])) ? $time - $last_function_dump['time'] : $time;

			// Calculate memory diff
			$memory = round( memory_get_usage()/1024 );
			$memdiff = (isset($last_function_dump['memory'])) ? $memory - $last_function_dump['memory'] : $memory;

			if ( $last_function_dump ) {
				$this->profiler_log( array( 'FUNCTION TEST', $diff . 'ms', $memdiff . 'kb', $last_function_dump['hook'], $last_function_dump['function'], join(',',$args) ) );
				$last_function_dump = false;
			}
			if ( in_array( $hook_now_processing, $hooks_to_dump ) ) {
				if ( is_array( $function_now_processing ) ) {
					if ( is_string( $function_now_processing[0] ) ) {
						$function_now_processing = join('::',$function_now_processing);
					} elseif ( is_object( $function_now_processing[0] ) ) {
						$function_now_processing = get_class($function_now_processing[0]).'::'.$function_now_processing[1];
					}
				}
				$last_function_dump = array(
					'hook' => $hook_now_processing,
					'function' => $function_now_processing,
					'time' => $time,
					'memory' => $memory,
				);
			}
		}

	}

	global $tribe_quick_profiler;
	$tribe_quick_profiler = new Tribe_Quick_Profiler();

	function tribe_function_dump( $hooks_to_dump, $hook_now_processing, $function_now_processing, $args=array() ) {
		global $tribe_quick_profiler;
		$tribe_quick_profiler->function_dump( $hooks_to_dump, $hook_now_processing, $function_now_processing );
	}
}

?>