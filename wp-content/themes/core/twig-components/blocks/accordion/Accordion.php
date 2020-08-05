<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Components\Component;
use \Tribe\Project\Blocks\Types\Accordion\Accordion as Accordion_Block;

/**
 * Class Accordion
 *
 * @property string   $layout
 * @property string   $content
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Accordion extends Component {

	public const LAYOUT            = 'layout';
	public const CONTENT           = 'content';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Accordion_Block::LAYOUT_STACKED,
			self::CONTENT           => '',
			self::HEADER            => '',
			self::CONTAINER_CLASSES => [ 'b-accordion__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-accordion__content' ],
			self::CLASSES           => [ 'c-block', 'b-accordion' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-block--' . $this->data[ self::LAYOUT ];
	}

}
