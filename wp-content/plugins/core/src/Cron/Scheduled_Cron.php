<?php

namespace Tribe\Project\Cron;

/**
 * Scheduled_Cron
 *
 * WP Cron handler for the scheduled tasks
 *
 * @see Scheduled::run_tasks()
 *
 *
 * @package Tribe\Project\Cron
 */
class Scheduled_Cron {
	const CRON_HOOK = 'core_scheduled_cron';
	const CRON_SCHEDULE = 'every_five_minutes';
	const LAST_CRON_OPTION = 'core_scheduled_last_run_time';

	const MANUAL_RUN_URL_PARAM = 'core_scheduled_last_run_time';


	public function hook(){
		if( !empty( $_REQUEST[ self::MANUAL_RUN_URL_PARAM ] ) ){
			add_action( 'init', [ $this, 'run_cron' ], 0, 9999 );
		}
		add_action( self::CRON_HOOK, [ $this, 'run_cron' ] );

		add_filter( 'cron_schedules', [ $this, 'add_five_minutes' ] );
	}


	public function add_five_minutes( $schedules ){
		$schedules[ self::CRON_SCHEDULE ] = [
			'interval' => 300,
			'display'  => __( 'Every Five Minutes', 'tribe' ),
		];

		return $schedules;
	}


	public function reschedule_cron(){
		$next_time = wp_next_scheduled( self::CRON_HOOK );
		wp_unschedule_event( $next_time, self::CRON_HOOK );
		$this->schedule_cron();
	}


	private function schedule_cron(){
		if( !wp_next_scheduled( self::CRON_HOOK ) ){
			wp_schedule_event( time(), self::CRON_SCHEDULE, self::CRON_HOOK );
		}

	}


	public function get_next_cron_time(){
		$time = wp_next_scheduled( self::CRON_HOOK );

		return $time;
	}


	public function get_last_cron_time(){
		$time = get_option( self::LAST_CRON_OPTION );

		return $time;
	}


	public function run_cron(){
		update_option( self::LAST_CRON_OPTION, time() );
		if( !defined( 'DOING_CRON' ) || !DOING_CRON ){
			$this->reschedule_cron();
		}

		Scheduled::instance()->run_tasks();
	}


	public function init(){
		$this->hook();
		$this->schedule_cron();
	}


	/**
	 *
	 * @static
	 *
	 * @return Scheduled_Cron
	 */
	public static function instance(){
		return tribe_project()->container()[ 'cron.scheduled_cron' ];
	}

}