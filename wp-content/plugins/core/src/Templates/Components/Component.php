<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Libs\Utils\Markup_Utils;

abstract class Component {

	protected $data;

	public function __construct( $args ) {
		$this->data = $args;
	}

	public function data(): array {
		return $this->data;
	}

	public function get_render(): string {
		ob_start();
		$this->render();

		return ob_get_clean();
	}

	/**
	 * Merge associative arrays of attributes into a string
	 *
	 * @param array ...$attributes
	 *
	 * @return string
	 */
	protected function merge_attrs( array ...$attributes ): string {
		$attributes = empty( $attributes ) ? [] : array_merge( ... $attributes );

		return Markup_Utils::concat_attrs( $attributes );
	}

	/**
	 * Sanitize and merge classes into a string for inclusion in a class attribute
	 *
	 * @param array ...$classes
	 *
	 * @return string
	 */
	protected function merge_classes( array ...$classes ): string {
		return Markup_Utils::class_attribute( array_merge( ... $classes ), true );
	}

	public function init() {}

	abstract public function render(): void;

}
