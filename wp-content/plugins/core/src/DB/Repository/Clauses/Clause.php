<?php
/**
 * Generic clause abstract.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository\Clauses;

/**
 * Class Clause.
 */
abstract class Clause implements Buildable {
	/**
	 * @var string The SQL command.
	 */
	protected $command;

	/**
	 * @var string The default value.
	 */
	protected $default;

	/**
	 * @var string Clause arg glue.
	 */
	protected $glue = ',';

	/**
	 * @var array Select arguments.
	 */
	protected $args;

	/**
	 * If true, this will make the default an impossible case.
	 *
	 * @var bool Whether or not we're performing a DELETE op.
	 */
	protected $is_delete;

	/**
	 * Clause constructor.
	 *
	 * @param array $args      An array of fields from which to select.
	 * @param bool  $is_delete Whether or not we're performing a DELETE op.
	 */
	public function __construct( array $args, $is_delete = false ) {
		$this->args      = $args;
		$this->is_delete = $is_delete;
	}

	/**
	 * @inheritDoc
	 */
	public function get_default_clause(): string {
		return sprintf(
			'%1$s %2$s',
			sanitize_text_field( $this->command ),
			sanitize_text_field( $this->default )
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_clause(): string {
		if ( empty( $this->args ) ) {
			return $this->get_default_clause();
		}

		return sprintf(
			'%1$s %2$s',
			sanitize_text_field( $this->command ),
			sanitize_text_field( implode( $this->glue, $this->args ) )
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_command(): string {
		return $this->command;
	}
}
