<?php

namespace Tribe\Project\Templates\Components\statistic;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

/**
 * Class Statistic_Controller
 */
class Statistic_Controller extends Abstract_Controller {
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const VALUE   = 'value';
	public const LABEL   = 'label';

	public string $tag;
	private array $classes;
	private array $attrs;
	private Deferred_Component $value;
	private Deferred_Component $label;

	public function __construct( array $args ) {
		$args = $this->parse_args( $args );

		$this->tag     = (string) $args[ self::TAG ];
		$this->classes = (array) $args[  self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->value   = $args[ self::VALUE ];
		$this->label   = $args[ self::LABEL ];
	}

	protected function defaults(): array {
		return [
			self::TAG     => 'div',
			self::CLASSES => [],
			self::ATTRS   => [],
			self::VALUE   => [],
			self::LABEL   => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-statistic' ],
		];
	}

	/**
	 * @return string
	 */
	public function tag(): string {
		return tag_escape( $this->tag );
	}

	/**
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function render_value() {
		if ( empty( $this->value ) ) {
			return '';
		}

		if ( empty( $this->value['tag'] ) ) {
			$this->value['tag'] = 'div';
		}

		$this->value['classes'][] = 'c-statistic__value';

		return $this->value;
	}

	public function render_label() {
		if ( empty( $this->label ) ) {
			return '';
		}

		if ( empty( $this->label['tag'] ) ) {
			$this->label['tag'] = 'div';
		}

		$this->label['classes'][] = 'c-statistic__label';

		return $this->label;
	}
}
