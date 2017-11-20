<?php

namespace Tribe\Project\CLI;

use cli\Table;
use Tribe\Project\Queues\Contracts\Queue;

class Queues extends \WP_CLI_Command {

	const DB_TABLE = 's1_queue';

	/**
	 * ## EXAMPLES
	 *
	 *     wp queues list
	 *
	 * @when after_wp_load
	 */
	public function list() {
		$queues = [];
		foreach ( Queue::instances() as $queue ) {
			/** @var Queue $queue */

			$parts    = explode( '\\', $queue->get_backend_type() );
			$queues[] = [
				'Queue'        => $queue->get_name(),
				'Backend'      => end( $parts ),
				'Pending Jobs' => $queue->count(),
			];
		}


		$table = new Table( $queues );
		$table->display();

	}

	public function add_table() {
		global $wpdb;

		$table_exists = $wpdb->query( $wpdb->prepare(
			'SHOW TABLES LIKE %s',
			$wpdb->prefix . self::DB_TABLE
		) );

		// Create table.
		if ( ! $table_exists ) {
			$wpdb->query( sprintf (
				'CREATE TABLE %s (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					task_handler varchar(255) NOT NULL,
					args text NOT NULL,
					priority int(3) NOT NULL,
					taken int(10)
				)',
				$wpdb->prefix . self::DB_TABLE
			) );

			\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );
		}

		\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
	}

}