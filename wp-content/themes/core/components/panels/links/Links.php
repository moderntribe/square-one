<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Components\Component;

class Links extends Component {

	public const LINKS = 'links';

	public const LAYOUT            = 'layout';
	public const CONTENT           = 'content';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const ROWS              = 'rows';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Accordion_Block::LAYOUT_STACKED,
			self::CONTENT           => '',
			self::HEADER            => '',
			self::CONTAINER_CLASSES => [ 'links__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'links__content' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--links' ],
			self::ATTRS             => [],
			self::ROWS              => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
	}

	// protected $path = __DIR__ . '/links.twig';
}
