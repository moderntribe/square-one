<?php
/**
 * Factory for building clauses.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

/**
 * Class Clause_Factory.
 */
class Clause_Factory {

	// Commands.
	public const SELECT = 'Select';
	public const WHERE  = 'Where';
	public const FROM   = 'From';

	/**
	 * Clause_Factory constructor.
	 *
	 * @param string $type The clause type to create.
	 * @param array  $args Clause arguments.
	 *
	 * @return string
	 */
	public function get_clause( string $type, array $args ): string {
		$classname = __NAMESPACE__ . '\\' . $type;

		/**
		 * @var Clause A SQL clause class.
		 */
		$clause = new $classname( $args );

		return $clause->get_clause();
	}
}
