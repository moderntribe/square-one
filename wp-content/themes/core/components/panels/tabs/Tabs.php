<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Components\Component;
use \Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;

/**
 * Class Tabs
 *
 * @property string   $layout
 * @property string   $content
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Tabs extends Component {

	public const LAYOUT            = 'layout';
	public const CONTENT           = 'content';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Tabs_Block::LAYOUT_HORIZONTAL,
			self::CONTENT           => '',
			self::HEADER            => '',
			self::CONTAINER_CLASSES => [ 'tabs__container' ],
			self::CONTENT_CLASSES   => [ 'tabs__content' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--tabs' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
	}

}
