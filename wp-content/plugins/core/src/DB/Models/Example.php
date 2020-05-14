<?php
/**
 * An activity record.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models;

use DateTime;
use DateTimeZone;
use Exception;
use Tribe\Project\DB\Models\Columns\DateTime_Column;
use Tribe\Project\DB\Models\Columns\Enum_Column;
use Tribe\Project\DB\Models\Columns\Integer_Column;
use Tribe\Project\DB\Models\Columns\String_Column;

/**
 * Class Activity.
 */
class Example extends Base_Storable {

	/**
	 * Object name.
	 *
	 * @see DB_Query
	 */
	public const NAME       = 'example';
	public const ID         = 'id';
	public const DATA       = 'data';
	public const CREATED_ON = 'created_on';

	/**
	 * @var int The unique ID.
	 */
	public $ID;

	/**
	 * @var string The datetime stamp when the activity record was created.
	 */
	public $created_on;

	/**
	 * @var mixed Any data associated with the Activity.
	 */
	public $data;

	/**
	 * Activity constructor.
	 *
	 * @param int    $ID
	 * @param string $created_on
	 * @param int    $user_id
	 * @param string $type
	 * @param string $data
	 */
	public function __construct( int $ID = 0, string $created_on = '', int $user_id = 0, string $type = '', string $data = '' ) {
		$this->ID         = $ID;
		$this->created_on = $created_on;
		$this->data       = $data;
	}

	/**
	 * @inheritDoc
	 */
	public function hydrate( array $data ): Storable {
		$values = $this->get_data_values( $data );

		$this->ID   = $values[ self::ID ];
		$this->data = $values[ self::DATA ];

		if ( ! empty( $values[ self::CREATED_ON ] ) ) {
			$this->created_on = $values[ self::CREATED_ON ];
		} else {
			try {
				$timezone         = new DateTimeZone( 'America/New_York' );
				$now              = new DateTime( 'now', $timezone );
				$this->created_on = $now->format( 'Y-m-d H:i:s' );
			} catch ( Exception $e ) {
				$this->created_on = '';
			}
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_columns(): array {
		return [
			self::ID         => new Integer_Column( self::ID, 'INT(11)', [
				'NOT NULL',
				'AUTO_INCREMENT',
			] ),
			self::CREATED_ON => new DateTime_Column( self::CREATED_ON ),
			self::DATA       => new String_Column( self::DATA, 'VARCHAR(500)' ),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_primary_key(): string {
		return self::ID;
	}
}
