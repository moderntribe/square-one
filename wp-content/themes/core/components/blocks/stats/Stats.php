<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Components\Component;
use \Tribe\Project\Blocks\Types\Stats\Stats as Stats_Block;

/**
 * Class Stats
 *
 * @property string   $layout
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $stats
 */
class Stats extends Component {

	public const LAYOUT            = 'layout';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const STATS             = 'stats';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Stats_Block::LAYOUT_STACKED,
			self::HEADER            => '',
			self::CONTAINER_CLASSES => [ 'b-stats__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-stats__content' ],
			self::CLASSES           => [ 'c-block', 'b-stats' ],
			self::ATTRS             => [],
			self::STATS             => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-block--' . $this->data[ self::LAYOUT ];
	}
}
