<?php
/**
 * Interface for various SQL query components.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

/**
 * Interface Buildable.
 */
interface Buildable {
	/**
	 * @return string The default value of a clause.
	 */
	public function get_default_clause(): string;

	/**
	 * @return string The full SQL clause.
	 */
	public function get_clause(): string;

	/**
	 * @return string The SQL command.
	 */
	public function get_command(): string;
}
