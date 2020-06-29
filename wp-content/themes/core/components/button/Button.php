<?php

namespace Tribe\Project\Templates\Components;

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


	public function init() {
		if ( ! empty( $this->data[ self::TYPE ] ) ) {
			$this->data[ self::ATTRS ]['type'] = $this->data[ self::TYPE ];
		}

		if ( ! empty( $this->data[ self::ARIA_LABEL ] ) ) {
			$this->data[ self::ATTRS ]['aria-label'] = $this->data[ self::ARIA_LABEL ];
		}

		$this->data[ self::ATTRS ]   = $this->merge_attrs( $this->data[ self::ATTRS ] ?? [] );
		$this->data[ self::CLASSES ] = $this->merge_classes( $this->data[ self::CLASSES ] ?? [] );
	}

	public function render(): void {
		?>
		<button {{ classes }} {{ attrs }}>
			{{ content }}
		</button>
		<?php
	}
}
