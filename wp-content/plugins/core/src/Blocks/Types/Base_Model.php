<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types;

use Tribe\Libs\ACF\Traits\With_Get_Field;

/**
 * The base block model.
 *
 * @package Tribe\Project\Blocks\Types
 */
abstract class Base_Model implements Model {

	use With_Get_Field;

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
