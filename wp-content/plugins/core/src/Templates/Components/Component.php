<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Libs\Utils\Markup_Utils;
use DI;
use Tribe\Project\Components\Handler;

abstract class Component {

	protected $data;

	public function __construct( $args = [] ) {
		$this->data = $args;
		$this->merge_defaults();
	}

	private function merge_defaults() {
		$this->data = wp_parse_args( $this->data, $this->defaults() );
	}

	public function data(): array {
		return $this->data;
	}

	public function get_render(): string {
		$handler = tribe_project()->container()->get( Handler::class );
		ob_start();
		$handler->render_component( static::class, $this->data );

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

	protected function defaults(): array {
		return [];
	}

	abstract public function render(): void;

}
