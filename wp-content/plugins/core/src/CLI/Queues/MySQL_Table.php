<?php

namespace Tribe\Project\CLI\Queues;

use Tribe\Project\CLI\Command;
use Tribe\Project\Queues\Backends\MySQL;
use Tribe\Project\Queues\Contracts\Backend;

class MySQL_Table extends Command {

	/**
	 * @var MySQL
	 */
	protected $backend;

	public function __construct( MySQL $backend ) {
		$this->backend = $backend;
		parent::__construct();
	}

	public function command() {
		return 'queues add-table';
	}

	public function arguments() {
		return [];
	}

	public function description() {
		return __( 'Adds the required MySQL table.', 'tribe' );
	}

	public function run_command( $args, $assoc_args ) {
		if ( 'Tribe\Project\Queues\Backends\MySQL' !== get_class( $this->backend ) ) {
			\WP_CLI::error( __( 'You cannot add a table a non-MySQL backend' ) );
		}

		if ( $this->backend->table_exists() ) {
			\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
			return;
		}

		do_action( 'tribe/project/queues/mysql/init_table' );
		\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );
	}
}