<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Button
 *
 * @property string   $type
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $content
 */
class Button extends Component {

	public const TYPE       = 'type';
	public const ARIA_LABEL = 'aria_label';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';

	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
			self::CONTENT => '',
		];
	}

	public function init() {
		if ( ! empty( $this->data[ self::TYPE ] ) ) {
			$this->data[ self::ATTRS ]['type'] = $this->data[ self::TYPE ];
		}

		if ( ! empty( $this->data[ self::ARIA_LABEL ] ) ) {
			$this->data[ self::ATTRS ]['aria-label'] = $this->data[ self::ARIA_LABEL ];
		}
	}
}
