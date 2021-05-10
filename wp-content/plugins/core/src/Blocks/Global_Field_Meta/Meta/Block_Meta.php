<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Field_Meta\Meta;

use InvalidArgumentException;
use Tribe\Libs\ACF\Field;

/**
 * Extend to create global block field meta.
 *
 * @package Tribe\Project\Blocks\Global_Field_Meta
 */
abstract class Block_Meta implements Meta {

	public const NAME = '';

	/**
	 * @var Field[]
	 */
	protected array $fields = [];

	public function __construct() {
		if ( static::NAME == '' ) {
			throw new InvalidArgumentException( sprintf( '%s requires a NAME constant', static::class ) );
		}
	}

	/**
	 * Define the fields to add.
	 *
	 * @see Block_Meta::add_field()
	 */
	abstract protected function add_fields(): void;

	/**
	 * @return Field[]
	 */
	public function get_fields(): array {
		if ( empty( $this->fields ) ) {
			$this->add_fields();
		}

		return $this->fields;
	}

	/**
	 * Add a field to the collection.
	 *
	 * @param Field $field
	 *
	 * @return $this
	 */
	protected function add_field( Field $field ): Block_Meta {
		$this->fields[] = $field;

		return $this;
	}
}
