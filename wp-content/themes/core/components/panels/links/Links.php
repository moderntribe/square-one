<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Components\Component;
use \Tribe\Project\Blocks\Types\Links\Links as Links_Block;

/**
 * Class Links
 *
 * @property string   $layout
 * @property string   $content
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Links extends Component {

	public const LAYOUT            = 'layout';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const LINKS             = 'links';
	public const LINKS_TITLE       = 'links_title';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Links_Block::LAYOUT_STACKED,
			self::HEADER            => '',
			self::CONTAINER_CLASSES => [ 'links__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'links__content' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--links' ],
			self::ATTRS             => [],
			self::LINKS             => [],
			self::LINKS_TITLE       => '',
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
	}
}
