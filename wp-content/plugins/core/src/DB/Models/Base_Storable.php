<?php
/**
 * The base storable object.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models;

use RuntimeException;
use Tribe\Project\DB\Exceptions\IncompatibleStorableException;
use Tribe\Project\DB\Models\Columns\Encodable;

/**
 * Class Base_Storable.
 */
abstract class Base_Storable implements Storable {

	/**
	 * The name of the object and table.
	 */
	public const NAME = '';

	/**
	 * @var string The prefix for this module.
	 */
	private static $package_prefix = 'so_';

	/**
	 * @inheritDoc
	 */
	abstract public function hydrate( array $data ): Storable;

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return static::NAME;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_classname(): string {
		return static::class;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_table_name(): string {
		return sprintf(
			'%s%s',
			self::$package_prefix,
			self::get_name()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_keys(): array {
		return array_keys( $this->get_columns() );
	}

	/**
	 * @inheritDoc
	 */
	public function case_sql( string $case, array $data = [] ): string {
		return '';
	}

	/**
	 * Gets the decoded values from the data.
	 *
	 * @param array $data The WPDb results.
	 *
	 * @return array The decoded values.
	 */
	protected function get_data_values( array $data ): array {
		$columns = $this->get_columns();
		$values  = [];

		foreach ( $data as $key => $value ) {
			try {
				/**
				 * @var Encodable
				 */
				$encodable      = $columns[ $key ];
				$values[ $key ] = $encodable->decode( $value );
			} catch ( RuntimeException $e ) {
				throw new IncompatibleStorableException( self::get_classname(), self::get_name() );
			}
		}

		return $values;
	}

	/**
	 * @inheritDoc
	 */
	abstract public function get_columns(): array;

	/**
	 * @inheritDoc
	 */
	abstract public function get_primary_key(): string;
}
