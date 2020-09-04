<?php

namespace Tribe\Project\Blocks\Types;

abstract class Base_Model {
	protected string $mode;
	protected array  $data;
	protected string $name;
	protected string $classes;

	abstract public function get_data(): array;

	/**
	 * Base_Controller constructor.
	 *
	 * @param $block
	 */
	public function __construct( $block ) {
		$this->mode    = $block['mode'] ?? 'preview';
		$this->data    = $block['data'] ?? [];
		$this->name    = $block['name'] ? str_replace( 'acf/', '', $block['name'] ) : '';
		$this->classes = $block['className'] ?? '';
	}

	/**
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public function get( $key, $default = false ) {
		$value = get_field( $key );
		//check to support nullable type properties in components.
		// ACF will in some cases return and empty string when we may want it to be null.
		// This allows us to always determine the default.
		return ! empty( $value )
			? $value
			: $default;
	}

	/**
	 * Get the "Additional Class Names" value from the block editor.
	 *
	 * @return array
	 */
	public function get_classes(): array {
		return explode( ' ', $this->classes );
	}
}
