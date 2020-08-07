<?php

namespace Tribe\Project\Templates\Components\content_block;


/**
 * Class Content_Block
 */
class Content_Block extends Abstract_Controller {

	/**
	 * @var string
	 */
	public $tag;

	/**
	 * @var string[]
	 */
	public $classes;

	/**
	 * @var string
	 */
	public $layout;

	/**
	 * @var string[]
	 */
	public $attrs;

	/**
	 * @var array
	 */
	public $leadin;

	/**
	 * @var array
	 */
	public $title;

	/**
	 * @var array
	 */
	public $text;

	/**
	 * @var array
	 */
	public $action;

	/**
	 * @var string
	 */
	public $action_component;

	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	public function __construct( $args ) {
		$this->tag     = $args[ 'tag' ] ?? 'div';
		$this->layout  = $args[ 'layout' ] ?? self::LAYOUT_LEFT;
		$this->attrs   = (array) ( $args[ 'attrs' ] ?? [] );
		$this->leadin  = (array) ( $args[ 'leadin' ] ?? [] );
		$this->title   = (array) ( $args[ 'title' ] ?? [] );
		$this->text    = (array) ( $args[ 'text' ] ?? [] );
		$this->action  = (array) ( $args[ 'action' ] ?? [] );
		$this->classes = array_merge(
			[ 'c-content-block', 'c-content-block--layout-' . $this->layout ],
			(array) ( $args[ 'classes' ] ?? [] )
		);
	}

    // I basically stopped here, ryan
	public function init() {
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
