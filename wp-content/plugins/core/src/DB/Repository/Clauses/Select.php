<?php
/**
 * The Select clause.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

/**
 * Class Select.
 */
class Select extends Clause {
	public const NAME = 'select';

	/**
	 * @inheritDoc
	 */
	protected $command = 'SELECT';

	/**
	 * @inheritDoc
	 */
	protected $default = '*';
}
