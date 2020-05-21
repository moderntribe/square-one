<?php
/**
 * Creates DB tables as defined in DB/Models.
 *
 * @package Square1-REST
 */

namespace Tribe\Project\CLI;

use Tribe\Libs\CLI\Command;
use Tribe\Project\DB\Models\Example;
use Tribe\Project\DB\Models\Storable_Factory;
use Tribe\Project\DB\Repository\Clauses\Clause_Factory;
use Tribe\Project\DB\Repository\WP_Table;
use WP_CLI;

/**
 * Class Create_tables.
 */
class Create_Tables extends Command {

	public const RECREATE = 'recreate';
	public const RENAME   = 'rename';

	public function description() {
		return __( 'Create the project tables.', 'tribe' );
	}

	/**
	 * @return array
	 */
	public function arguments() {
		return [
			[
				'type'        => 'flag',
				'name'        => self::RECREATE,
				'optional'    => true,
				'description' => __( 'Drop the tables first, and recreate them.', 'tribe' ),
				'default'     => false,
			],
			[
				'type'        => 'flag',
				'name'        => self::RENAME,
				'optional'    => true,
				'description' => __( 'Migrate old table names.', 'tribe' ),
				'default'     => false,
			],
		];
	}

	public function command() {
		return 'create-tables';
	}

	/**
	 * Command body.
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @throws WP_CLI\ExitException
	 */
	public function run_command( $args, $assoc_args ) {
		$assoc_args = array_merge( [
			self::RECREATE => false,
			self::RENAME   => false,
		], $assoc_args );
		global $wpdb;

		$clause_f   = new Clause_Factory();
		$storable_f = new Storable_Factory();
		$ex         = new Example();

		$ex_table = new WP_Table( $wpdb, $clause_f, $ex, $storable_f );

		if ( $assoc_args[ self::RENAME ] ) {
			$ex_table->db_rename( $wpdb->prefix . 'malformed_legacy_table_garble', $ex_table->get_prefixed_name() );
		}

		if ( $ex_table->db_upgrade( $assoc_args[ self::RECREATE ] ) ) {
			WP_CLI::success( 'Done.' );

			exit();
		}

		WP_CLI::error( 'Failed' );
	}
}
