<?php

namespace Tribe\Tests\Factories;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;

class ACF_Meta_Group extends \Tribe\Libs\ACF\ACF_Meta_Group {

	/** @var array */
	protected $last_inserted_group = [];

	/** @var string $name */
	protected $name;

	/** @var array $fields Array of ACF_Field */
	protected $fields = [];

	/**
	 * Kill parent constructor so we can handle configurations ourselves.
	 *
	 * Also checks if the project meets the minimum requirements to use this Factory.
	 *
	 * @throws \Exception
	 */
	public function __construct() {
		if ( ! function_exists( 'acf' ) ) {
			throw new \Exception( 'ACF must be active in order to use this Factory.' );
		}

		$minimum_version = "5.7.10";
		$acf_version     = acf()->version;

		if ( version_compare( $acf_version, $minimum_version, "<" ) ) {
			throw new \Exception( sprintf(
				'ACF must be version %s or higher in order to use this Factory. You have %s.',
				$minimum_version,
				$acf_version
			) );
		}
	}

	/**
	 * Clear ACF groups and fields created by the factory
	 */
	public function __destruct() {
		$last_inserted_group = $this->get_last_inserted_group();

		if ( ! empty( $last_inserted_group ) ) {
			// Removes ACF groups and fields created by the factory between each test
			foreach ( $last_inserted_group['fields'] as $field ) {
				acf_remove_local_field( $field['key'] );
				acf_get_local_store( 'fields' )->remove( $field['key'] );
			}

			acf_remove_local_field_group( $last_inserted_group['key'] );
			acf_get_store( 'field-groups' )->remove( $last_inserted_group['key'] );
		}
	}

	/**
	 * This is a rather complex factory, this is how it works:
	 *
	 * On tests, create Factory/ACF_Meta_Group instance
	 * Chain with "with_name", "with_location", etc
	 * If you want fields assigned to it, chain "with_fields()" as well, which
	 * must be an array of Factories/ACF_Field instances
	 * Call the create() method bellow, it will register the group in ACF
	 *
	 * @return $this
	 */
	public function create() {
		// Assign to "post" if location was not specified.
		if ( empty( $this->object_types ) ) {
			$this->object_types = [
				'post_types' => [ 'post' ],
			];
		}

		/**
		 * Before getting to this point, you should configure this object
		 * with "with_name", "with_location", "with_fields", etc.
		 */
		$this->register_group();

		return $this;
	}

	/**
	 * Register the group in ACF
	 */
	public function register_group() {
		$this->last_inserted_group = $this->get_group_config();
		parent::register_group();
	}

	/**
	 * @param array $location
	 *
	 * @return $this
	 */
	public function with_location( array $location ) {
		// Refer to Meta_Group::object_types
		$this->object_types = $location;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function with_name( string $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @param Field ...$fields
	 *
	 * @return $this
	 */
	public function with_fields( Field ...$fields ) {
		$this->fields = $fields;

		return $this;
	}

	/**
	 * @return array The ACF config array for the field group
	 */
	protected function get_group_config() {
		if ( empty( $this->name ) ) {
			throw new \RuntimeException( sprintf(
				'%s requires a name. Assign one with "with_fields(\'string\')"',
				__CLASS__
			) );
		}

		$group = new Group( $this->name );

		/** @var Field $field */
		foreach ( $this->fields as $field ) {
			$group->add_field( $field );
		}

		return $group->get_attributes();
	}

	/**
	 * @return array The meta keys that this field will handle.
	 *               While these will probably directly correspond
	 *               to meta keys in the database, there is no
	 *               guaranteed, as the key may correspond to
	 *               a computed/aggregate value.
	 */
	public function get_keys() {
		$keys = [];
		/** @var Field $field */
		foreach ( $this->fields as $field ) {
			$keys[] = $field->get_attributes()['name'];
		}

		return $keys;
	}

	/**
	 * Useful to reset ACF groups store between tests
	 *
	 * @return array
	 */
	public function get_last_inserted_group() {
		return $this->last_inserted_group;
	}
}