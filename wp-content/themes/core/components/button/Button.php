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
 * @property string   $wrapper_tag
 * @property string[] $wraper_classes
 * @property string[] $wrapper_attrs
 */
class Button extends Component {

	public const TYPE            = 'type';
	public const ARIA_LABEL      = 'aria_label';
	public const CLASSES         = 'classes';
	public const ATTRS           = 'attrs';
	public const CONTENT         = 'content';
	public const WRAPPER_TAG     = 'wrapper';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const WRAPPER_ATTRS   = 'wrapper_attrs';

	protected function defaults(): array {
		return [
			self::CLASSES         => [],
			self::ATTRS           => [],
			self::CONTENT         => '',
			self::WRAPPER_TAG     => '',
			self::WRAPPER_CLASSES => [],
			self::WRAPPER_ATTRS   => [],
		];
	}

	public function init() {
		if ( ! empty( $this->data[ self::WRAPPER_TAG ] ) ) {
			$this->twig_file = 'button-with-wrapper.twig';
		}

		if ( ! empty( $this->data[ self::TYPE ] ) ) {
			$this->data[ self::ATTRS ]['type'] = $this->data[ self::TYPE ];
		}

		if ( ! empty( $this->data[ self::ARIA_LABEL ] ) ) {
			$this->data[ self::ATTRS ]['aria-label'] = $this->data[ self::ARIA_LABEL ];
		}
	}
}
