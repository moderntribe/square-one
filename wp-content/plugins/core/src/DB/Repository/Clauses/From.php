<?php
/**
 * The From clause.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

use http\Exception\RuntimeException;

/**
 * Class From.
 */
class From extends Clause {
	const NAME = 'from';

	/**
	 * @inheritDoc
	 */
	protected $command = 'FROM';

	/**
	 * From constructor.
	 *
	 * @param array $args Arguments.
	 * @throws RuntimeException
	 */
	public function __construct( array $args ) {
		if ( empty( $args ) || 1 > count( $args ) ) {
			throw new RuntimeException( 'From clause requires one and only one argument.' );
		}

		$this->default = array_values( $args )[0];

		parent::__construct( $args );
	}
}
