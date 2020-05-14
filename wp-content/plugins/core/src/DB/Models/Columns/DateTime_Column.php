<?php
/**
 * Datetime column data type.
 *
 * Included types: DATETIME.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

use DateTime;
use DateTimeZone;
use Exception;
use Tribe\Project\DB\Exceptions\InvalidDataForColumnException;

/**
 * Class DateTime_Column.
 */
class DateTime_Column extends Base_Column {
	/**
	 * @inheritDoc
	 */
	public const TYPE = 'Boolean';

	// Formats.
	public const EMPTY_FORMAT = '0000-00-00 00:00:00';
	public const FORMAT       = 'Y-m-d H:i:s';

	/**
	 * @var DateTimeZone The time zone object.
	 */
	private $datetimezone;

	/**
	 * @inheritDoc
	 */
	protected $wpdb_type = '%s';

	/**
	 * DateTime_Column constructor.
	 *
	 * @param string $key      The key.
	 * @param array  $args     Any additional arguments, e.g., AUTO-INCREMENT.
	 * @param string $timezone The timezone string for the DateTimeZone object.
	 */
	public function __construct( string $key, array $args = [], string $timezone = 'UTC' ) {
		$this->datetimezone = new DateTimeZone( 'UTC' );

		parent::__construct( $key, 'DATETIME', $args );
	}

	/**
	 * @inheritDoc
	 */
	public function encode( $value ): ?string {
		if ( empty( $value ) || $value === self::EMPTY_FORMAT ) {
			return null;
		}

		try {
			$datetime = new DateTime( $value, $this->datetimezone );

			return $datetime->format( 'Y-m-d H:i:s' );
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function decode( $value ): string {
		if ( empty( $value ) || $value === self::EMPTY_FORMAT ) {
			return self::EMPTY_FORMAT;
		}

		if ( is_numeric( $value ) || is_string( $value ) ) {
			try {
				$value = new DateTime( (string) $value, $this->datetimezone );
			} catch ( Exception $e ) {
				return self::EMPTY_FORMAT;
			}
		} elseif ( $value instanceof DateTime ) {
			$value->setTimezone( $this->datetimezone );
		} else {
			throw new InvalidDataForColumnException( $this, $value );
		}

		return $value->format( self::FORMAT );
	}
}
