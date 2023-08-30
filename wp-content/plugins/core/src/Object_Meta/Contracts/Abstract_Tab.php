<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta\Contracts;

abstract class Abstract_Tab implements With_Key, With_Title {

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	abstract public function get_fields(): array;

	/**
	 * @return string[]
	 */
	abstract public function get_keys(): array;

	/**
	 * @param string|int $key
	 * @param string|int $post_id
	 *
	 * @return mixed|null
	 */
	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

}
