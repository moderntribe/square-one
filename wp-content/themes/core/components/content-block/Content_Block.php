<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Content_Block
 *
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $container_classes
 * @property array    $leadin
 * @property array    $title
 * @property array    $text
 * @property array    $action
 * @property string   $action_component
 */
class Content_Block extends Component {

	public const TAG              = 'tag';
	public const CLASSES          = 'classes';
	public const LAYOUT           = 'layout';
	public const ATTRS            = 'attrs';
	public const LEADIN           = 'leadin';
	public const TITLE            = 'title';
	public const TEXT             = 'text';
	public const ACTION           = 'action';
	public const ACTION_COMPONENT = 'action_component';

	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	protected function defaults(): array {
		return [
			self::TAG              => 'div',
			self::LAYOUT           => self::LAYOUT_LEFT,
			self::CLASSES          => [ 'c-content-block' ],
			self::ATTRS            => [],
			self::LEADIN           => [],
			self::TITLE            => [],
			self::TEXT             => [],
			self::ACTION           => [],
			self::ACTION_COMPONENT => 'link/Link.php',
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][]                         = 'c-content-block';
		$this->data[ self::CLASSES ][]                         = 'c-content-block--layout-' . $this->data[ self::LAYOUT ];
		$this->data[ self::LEADIN ][ Text::CLASSES ][]         = 'c-content-block__leadin';
		$this->data[ self::TITLE ][ Text::CLASSES ][]          = 'c-content-block__title';
		$this->data[ self::TEXT ][ Text::CLASSES ][]           = 'c-content-block__text';
		$this->data[ self::ACTION ][ Link::WRAPPER_CLASSES ][] = 'c-content-block__cta';
	}

	public function render(): void {
		if (
			empty( $this->data[ self::LEADIN ][ Text::TEXT ] ) &&
			empty( $this->data[ self::TITLE ][ Text::TEXT ] ) &&
			empty( $this->data[ self::TEXT ][ Text::TEXT ] ) &&
			empty( $this->data[ self::ACTION ][ 'content' ] )
		) {
			return;
		}

		parent::render();
	}
}
