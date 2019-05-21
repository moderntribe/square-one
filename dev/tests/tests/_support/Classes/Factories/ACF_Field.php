<?php

namespace Tribe\Tests\Factories;

use Tribe\Libs\ACF\Field;

class ACF_Field {

	/** @var string */
	protected $field_type;

	/** @var string */
	protected $field_name;

	/**
	 * @param string|null $field_name
	 */
	public function create() {
		// Use a random string as name if none provided
		$name = $this->field_name ?? wp_generate_password( 12, false );
		$type = $this->field_type ?? 'text';

		$field = new Field( $name );
		$field->set_attributes( [
			'label' => $name,
			'name'  => $name,
			'type'  => $type,
		] );

		return $field;
	}

	/**
	 * @param string $field_name
	 *
	 * @return $this
	 */
	public function with_name( string $field_name ) {
		$this->field_name = $field_name;

		return $this;
	}

	/**
	 * @param string $field_type
	 *
	 * @return $this
	 */
	public function with_type( string $field_type ) {
		$this->field_type = $field_type;

		return $this;
	}
}