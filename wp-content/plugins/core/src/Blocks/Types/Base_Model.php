<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types;

use Tribe\Libs\ACF\Traits\With_Get_Field;
use Tribe\Project\Blocks\Contracts\Model;

abstract class Base_Model implements Model {

	use With_Get_Field;

	/**
	 * The data passed to the controller.
	 *
	 * @var mixed[]
	 */
	protected array $data;

	/**
	 * The ACF block field data.
	 *
	 * @var mixed[]
	 */
	protected array $field_data;

	protected string $anchor;
	protected string $classes;
	protected string $id;
	protected string $mode;
	protected string $name;

	/**
	 * Set the state of the model data that will be passed to the
	 * block's controller.
	 *
	 * @return array<string, mixed>
	 */
	abstract protected function init_data(): array;

	/**
	 * Base_Controller constructor.
	 *
	 * @param mixed[] $block
	 */
	public function __construct( array $block ) {
		$this->anchor     = $block['anchor'] ?? '';
		$this->classes    = $block['className'] ?? '';
		$this->field_data = $block['data'] ?? [];
		$this->id         = $block['id'] ?? '';
		$this->mode       = $block['mode'] ?? 'preview';
		$this->name       = ( $block['name'] ?? '' ) ? str_replace( 'acf/', '', $block['name'] ) : '';

		// This must always run last
		$this->data = $this->init_data();
	}

	/**
	 * Set the model's controller data.
	 *
	 * @param mixed[] $data
	 *
	 * @return static
	 */
	public function set_data( array $data ): self {
		$this->data = $data;

		return $this;
	}

	/**
	 * A multidimensional array of model data passed to the controller.
	 *
	 * @return array<string, mixed>
	 */
	public function get_data(): array {
		return $this->data;
	}

	/**
	 * Get the assigned ACF block ID.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * Get the block name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get the "Additional Class Names" value from the block editor.
	 *
	 * @return array
	 */
	public function get_classes(): array {
		return explode( ' ', $this->classes );
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
			$attrs['id '] = $this->anchor;
		}

		return $attrs;
	}

}
