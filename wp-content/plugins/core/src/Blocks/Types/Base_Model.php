<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types;

/**
 * The base block model.
 *
 * @package Tribe\Project\Blocks\Types
 */
abstract class Base_Model implements Model {

	protected string $mode;
	protected array  $data;
	protected string $name;
	protected string $classes;
	protected string $anchor;

	/**
	 * Build A multidimensional array of model data.
	 *
	 * @return mixed[]
	 */
	abstract protected function set_data(): array;

	/**
	 * A multidimensional array of model data.
	 *
	 * @return mixed[]
	 */
	public function get_data(): array {

		/**
		 * Allow developers to attach extra model data.
		 *
		 * @param mixed[] $data The existing data on the model.
		 * @param Base_Model $model The model instance.
		 */
		return (array) apply_filters( 'tribe/block/model/data', $this->set_data(), $this );
	}

	/**
	 * Base_Controller constructor.
	 *
	 * @param array $block
	 */
	public function __construct( array $block ) {
		$this->mode    = $block['mode'] ?? 'preview';
		$this->data    = $block['data'] ?? [];
		$this->name    = $block['name'] ? str_replace( 'acf/', '', $block['name'] ) : '';
		$this->classes = $block['className'] ?? '';
		$this->anchor  = $block['anchor'] ?? '';
	}

	/**
	 * Retrieve data from an ACF field.
	 *
	 * @param int|string $key ACF key identifier.
	 * @param false  $default The default value if the field doesn't exist.
	 *
	 * @return mixed
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
	 * Get any block attributes from the block editor.
	 *
	 * @return array|string[]
	 */
	protected function get_attrs(): array {
		$attrs = [];

		// "HTML Anchor" attribute
		if ( ! empty( $this->anchor ) ) {
			$attrs[ 'id '] = $this->anchor;
		}

		return $attrs;
	}

	/**
	 * Get the "Additional Class Names" value from the block editor.
	 *
	 * @return string[]
	 */
	public function get_classes(): array {
		return explode( ' ', $this->classes );
	}
}
