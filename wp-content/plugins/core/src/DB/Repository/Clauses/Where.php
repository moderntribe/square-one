<?php
/**
 * The Where clause.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

/**
 * Class Where.
 */
class Where extends Clause {
	/**
	 * Name of the command.
	 */
	public const NAME = 'where';

	/**
	 * @inheritDoc
	 */
	protected $command = 'WHERE';

	/**
	 * @inheritDoc
	 */
	protected $default = '1=1';

	/**
	 * @inheritDoc
	 */
	protected $glue = ' AND ';

	/**
	 * @inheritDoc
	 */
	public function get_default_clause(): string {
		if ( $this->is_delete ) {
			return '1=0';
		}

		return sprintf(
			'%1$s %2$s',
			sanitize_text_field( $this->command ),
			sanitize_text_field( $this->default )
		);
	}
}
