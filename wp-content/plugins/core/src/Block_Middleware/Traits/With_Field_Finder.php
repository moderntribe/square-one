<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Traits;

/**
 * @mixin \Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware
 */
trait With_Field_Finder {

	/**
	 * Find a field in a sea of recursive fields.
	 *
	 * @note This is a recursive method!
	 *
	 * @param \Tribe\Libs\ACF\Field[]|\Tribe\Libs\ACF\Contracts\Has_Sub_Fields[] $fields
	 * @param string                                                             $key The key to find, note that ACF prefixes most keys with "field_" but not all the time.
	 *
	 * @return \Tribe\Libs\ACF\Field|\Tribe\Libs\ACF\Contracts\Has_Sub_Fields|null
	 */
	protected function find_field( array $fields, string $key ) {
		$subfields = [];

		// Check all top level fields first
		foreach ( $fields as $field ) {
			$field_key = $field->get( 'key' );

			if ( $field_key === $key ) {
				return $field;
			}

			// Collect any subfields to process after
			$subfields = array_merge(
				$subfields,
				method_exists( $field, 'get_fields' )
						? $field->get_fields()
						: (array) $field->get( 'fields' )
			);
		}

		if ( $subfields ) {
			return $this->find_field( $subfields, $key );
		}

		return null;
	}

}
