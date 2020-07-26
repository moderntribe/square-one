<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Statistic
 *
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $container_classes
 * @property array    $value
 * @property array    $label
 */
class Statistic extends Component {

	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const VALUE   = 'value';
	public const LABEL   = 'label';

	protected function defaults(): array {
		return [
			self::TAG     => 'div',
			self::CLASSES => [ 'c-statistic' ],
			self::ATTRS   => [],
			self::VALUE   => [],
			self::LABEL   => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][]                = 'c-statistic';
		$this->data[ self::VALUE ][ Text::CLASSES ][] = 'c-statistic__value';
		$this->data[ self::LABEL ][ Text::CLASSES ][] = 'c-statistic__label';
	}

	public function render(): void {
		if (
			empty( $this->data[ self::VALUE ][ Text::TEXT ] ) &&
			empty( $this->data[ self::LABEL ][ Text::TEXT ] )
		) {
			return;
		}

		parent::render();
	}
}
