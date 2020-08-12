<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Components\Component;

/**
 * Class Logos
 *
 * @property string[] $logos
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Logos extends Component {

	public const LOGOS             = 'logos';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::LOGOS             => [],
			self::HEADER            => [],
			self::CONTAINER_CLASSES => [ 'b-logos__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-logos__list' ],
			self::CLASSES           => [ 'c-block', 'b-logos' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->data[ self::CONTENT_CLASSES ][] = sprintf( 'b-logos--count-%d', count( $this->data[ self::LOGOS ] ) );
	}

}
