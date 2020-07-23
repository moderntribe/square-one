<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Components\Component;
use \Tribe\Project\Blocks\Types\Lead_Form\Lead_Form as Lead_Form_Block;

/**
 * Class Lead_Form
 *
 * @property string   $layout
 * @property string   $content
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Lead_Form extends Component {

	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const CONTENT           = 'content';
	public const FORM              = 'form';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const FORM_CLASSES      = 'form_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::WIDTH             => Lead_Form_Block::WIDTH_GRID,
			self::LAYOUT            => Lead_Form_Block::LAYOUT_CENTER,
			self::CONTENT           => '',
			self::FORM              => '',
			self::CONTAINER_CLASSES => [ 'lead-form__container', ],
			self::CONTENT_CLASSES   => [ 'lead-form__content' ],
			self::FORM_CLASSES      => [ 'lead-form__form' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--lead-form' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::WIDTH ];

		if ( $this->data[ self::WIDTH ] === Lead_Form_Block::WIDTH_GRID ) {
			$this->data[ self::CLASSES ][] = 'l-container';
		}

		if ( $this->data[ self::WIDTH ] === Lead_Form_Block::WIDTH_FULL ) {
			$this->data[ self::CONTAINER_CLASSES ][] = 'l-container';
		}
	}

}
