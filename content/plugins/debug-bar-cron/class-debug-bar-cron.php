<?php

/**
 * Add a new Debug Bar Panel.
 */
class ZT_Debug_Bar_Cron extends Debug_Bar_Panel {

	/**
	 * Holds all of the cron events.
	 *
	 * @var array
	 */
	private $_crons;

	/**
	 * Holds only the cron events initiated by WP core.
	 *
	 * @var array
	 */
	private $_core_crons;

	/**
	 * Holds the cron events created by plugins or themes.
	 *
	 * @var array
	 */
	private $_user_crons;

	/**
	 * Total number of cron events
	 *
	 * @var int
	 */
	private $_total_crons = 0;

	/**   
	 * Whether cron is being executed or not.
	 * 
	 * @var string
	 */
	private $_doing_cron = 'No';

	/**
	 * Give the panel a title and set the enqueues.
	 *
	 * @return void
	 */
	public function init() {
		$this->title( __( 'Cron', 'debug-bar' ) );
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		add_action( 'admin_print_styles', array( $this, 'print_styles' ) );
	}

	/**
	 * Enqueue styles.
	 *
	 * @return  void
	 */
	public function print_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		wp_enqueue_style( 'zt-debug-bar-cron', plugins_url( "css/debug-bar-cron$suffix.css", __FILE__ ), array(), '20120325' );
	}

	/**
	 * Show the menu item in Debug Bar.
	 *
	 * @return  void
	 */
	public function prerender() {
		$this->set_visible( true );
	}

	/**
	 * Show the contents of the page.

	 * @return  void
	 */
	public function render() {
		$this->get_crons();

		$this->_doing_cron = get_transient( 'doing_cron' ) ? __( 'Yes', 'zt-debug-bar-cron' ) : __( 'No', 'zt-debug-bar-cron' );

		// Get the time of the next event
		$cron_times = array_keys( $this->_crons );
		$unix_time_next_cron = $cron_times[0];
		$time_next_cron = date( 'Y-m-d H:i:s', $unix_time_next_cron );

		$human_time_next_cron = human_time_diff( $unix_time_next_cron );

		// Add a class if past current time and doing cron is not running
		$times_class = time() > $unix_time_next_cron && 'No' == $this->_doing_cron ? ' past' : '';

		echo '<div id="debug-bar-cron">';
		echo '<h2><span>' . __( 'Total Events', 'zt-debug-bar-cron' ) . ':</span>' . (int) $this->_total_crons . '</h2>';
		echo '<h2><span>' . __( 'Doing Cron', 'zt-debug-bar-cron' ) . ':</span>' . $this->_doing_cron . '</h2>';
		echo '<h2 class="times' . esc_attr( $times_class ) . '"><span>' . __( 'Next Event', 'zt-debug-bar-cron' ) . ':</span>' . $time_next_cron . '<br />' . $unix_time_next_cron . '<br />' . $human_time_next_cron . $this->display_past_time( $unix_time_next_cron ) . '</h2>';
		echo '<h2><span>' . __( 'Current Time', 'zt-debug-bar-cron' ) . ':</span>' . date( 'H:i:s' ) . '</h2>';
		echo '<div class="clear"></div>';

		echo '<h3>' . __( 'Custom Events', 'zt-debug-bar-cron' ) . '</h3>';

		if ( ! is_null( $this->_user_crons ) )
			$this->display_events( $this->_user_crons );
		else
			echo '<p>' . __( 'No Custom Events scheduled.', 'zt-debug-bar-cron' ) . '</p>';

		echo '<h3>' . __( 'Schedules', 'zt-debug-bar-cron' ) . '</h3>';

		$this->display_schedules();

		echo '<h3>' . __( 'Core Events', 'zt-debug-bar-cron' ) . '</h3>';

		if ( ! is_null( $this->_core_crons ) )
			$this->display_events( $this->_core_crons );
		else
			echo '<p>' . __( 'No Core Events scheduled.', 'zt-debug-bar-cron' ) . '</p>';

		echo '</div>';
	}

	/**
	 * Gets all of the cron jobs.
	 *
	 * This function sorts the cron jobs into core crons, and custom crons. It also tallies
	 * a total count for the crons as this number is otherwise tough to get.
	 *
	 * @return  array   Array of crons.
	 */
	private function get_crons() {
		if ( ! is_null( $this->_crons ) )
			return $this->_crons;

		if ( ! $crons = _get_cron_array() )
			return $this->_crons;

		$this->_crons = $crons;

		// Lists all crons that are defined in WP Core
		$core_cron_hooks = array(
			'wp_scheduled_delete',
			'upgrader_scheduled_cleanup',
			'importer_scheduled_cleanup',
			'publish_future_post',
			'akismet_schedule_cron_recheck',
			'akismet_scheduled_delete',
			'do_pings',
			'wp_version_check',
			'wp_update_plugins',
			'wp_update_themes'
		);

		// Sort and count crons
		foreach ( $this->_crons as $time => $time_cron_array ) {
			foreach ( $time_cron_array as $hook => $data ) {
				$this->_total_crons++;

				if ( in_array( $hook, $core_cron_hooks ) )
					$this->_core_crons[ $time ][ $hook ] = $data;
				else
					$this->_user_crons[ $time ][ $hook ] = $data;
			}
		}

		return $this->_crons;
	}

	/**
	 * Displays the events in an easy to read table.
	 *
	 * @param   array   $events     Array of events.
	 * @return  void|string         Void on failure; table display of events on success.
	 */
	private function display_events( $events ) {
		if ( is_null( $events ) || empty( $events ) )
			return;

		$class = 'odd';

		echo '<table class="zt-debug-bar-cron-event-table" cellspacing="0">';
		echo '<thead>';
		echo '<th width="180px">' . __( 'Next Execution', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="25%">' . __( 'Hook', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="20%">' . __( 'Interval Hook', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="120px">' . __( 'Interval Value', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="25%">' . __( 'Args', 'zt-debug-bar-cron' ) . '</th>';
		echo '</thead>';

		foreach ( $events as $time => $time_cron_array ) {
			foreach ( $time_cron_array as $hook => $data ) {
				// Add a class if past current time
				$times_class = time() > $time && 'No' == $this->_doing_cron ? ' class="past"' : '';

				echo '<tr class="' . $class . '">';
				echo '<td valign="top"' . $times_class . '>' . date( 'Y-m-d H:i:s', $time ) . '<br />' . $time . '<br />' . human_time_diff( $time ) . $this->display_past_time( $time ) . '</td>';
				echo '<td valign="top">' . wp_strip_all_tags( $hook ) . '</td>';

				foreach ( $data as $hash => $info ) {
					// Report the schedule
					echo '<td valign="top">';
					if ( $info['schedule'] )
						echo wp_strip_all_tags( $info['schedule'] );
					else
						echo 'Single Event';
					echo '</td>';

					// Report the interval
					echo '<td valign="top">';
					if ( isset( $info['interval'] ) ) {
						echo wp_strip_all_tags( $info['interval'] ) . 's<br />';
						echo $info['interval'] / 60 . 'm<br />';
						echo $info['interval'] / ( 60  * 60 ). 'h';
					} else {
						echo 'Single Event';
					}
					echo '</td>';

					// Report the args
					echo '<td valign="top">';
					if ( ! empty( $info['args'] ) ) {
						foreach ( $info['args'] as $key => $value ) {
							echo wp_strip_all_tags( $key ) . ' => ' . wp_strip_all_tags( $value ) . '<br />';
						}
					} else {
						echo 'No Args';
					}
					echo '</td>';
				}

				echo '</tr>';
				$class = ( 'odd' == $class ) ? 'even' : 'odd';
			}
		}

		echo '</table>';
	}

	/**
	 * Displays all of the schedules defined.
	 *
	 * @return  void
	 */
	private function display_schedules() {
		echo '<table class="zt-debug-bar-cron-event-table" cellspacing="0">';
		echo '<thead>';
		echo '<th width="180px">' . __( 'Interval Hook', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="25%">' . __( 'Interval (S)', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="20%">' . __( 'Interval (M)', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="120px">' . __( 'Interval (H)', 'zt-debug-bar-cron' ) . '</th>';
		echo '<th width="25%">' . __( 'Display Name', 'zt-debug-bar-cron' ) . '</th>';
		echo '</thead>';

		$class = 'odd';

		foreach ( wp_get_schedules() as $interval_hook => $data ) {
			echo '<tr class="' . $class . '">';
			echo '<td valign="top">' . esc_html( $interval_hook ) . '</td>';
			echo '<td valign="top">' . wp_strip_all_tags( $data['interval'] ) . '</td>';
			echo '<td valign="top">' . wp_strip_all_tags( $data['interval'] ) / 60 . '</td>';
			echo '<td valign="top">' . wp_strip_all_tags( $data['interval'] ) / ( 60  * 60 ). '</td>';
			echo '<td valign="top">' . esc_html( $data['display'] ) . '</td>';
			echo '</tr>';

			$class = ( 'odd' == $class ) ? 'even' : 'odd';
		}

		echo '</table>';
	}

	/**
	 * Compares time with current time and outputs 'ago' if current time is greater that even time.
	 *
	 * @param   int     $time   Unix time of event.
	 * @return  string
	 */
	private function display_past_time( $time ) {
		return time() > $time ? ' ' . __( 'ago', 'zt-debug-bar-cron' ) : '';
	}
}