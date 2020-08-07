<?php

namespace Tribe\Project\Templates\Components\content_block;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Content_Block
 */
class Controller extends Abstract_Controller {

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
	 * @var string
	 */
	public $leadin;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string[]
	 */
	public $content;

	/**
	 * @var array
	 */
	public $cta;

	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	public function __construct( $args ) {
		$this->tag     = $args[ 'tag' ] ?? 'div';
		$this->classes = array_merge( [ 'c-content-block', 'c-content-block--layout-' . $this->layout ], (array) ( $args[ 'classes' ] ?? [] ) );
		$this->attrs   = (array) ( $args[ 'attrs' ] ?? [] );
		$this->layout  = $args[ 'layout' ] ?? self::LAYOUT_LEFT;

		$this->leadin  = $args[ 'leadin' ] ?? '';
		$this->leadin_classes = array_merge( [ 'c-content-block__leadin' ], (array) ( $args[ 'leadin_classes' ] ?? [ 'h6' ] ) );

		$this->title   = $args[ 'title' ] ?? '';
		$this->title_classes = array_merge( [ 'c-content-block__title' ], (array) ( $args[ 'title_classes' ] ?? [ 'h1' ] ) );

		$this->content    = $args[ 'content' ] ?? '';
		$this->content_classes = array_merge( [ 'c-content-block__content', 't-sink', 's-sink' ], (array) ( $args[ 'content_classes' ] ?? [] ) );

		$this->cta  = (array) ( $args[ 'cta' ] ?? [] );
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function wrapper_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function render_cta() {
		//if ( empty( $this->cta ) ) {
		//	return '';
		//}

		// TODO: once have wrapper to more easily pass child component args through parent component
		// circle back here and update and add on required classes
		// $this->cta_classes = array_merge( [ 'c-content-block__cta' ], (array) ( $args[ 'action_classes' ] ?? [] ) );
		/*
		return [
			Link::URL             => $cta['url'],
			Link::CONTENT         => $cta['text'] ?: $cta['url'],
			Link::TARGET          => $cta['target'],
			Link::CLASSES         => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			Link::WRAPPER_TAG     => 'p',
			Link::WRAPPER_CLASSES => [ 'b-hero__cta' ],
		];
		*/

		//return get_template_part( 'components/link/link', null, $action );
	}

	private function get_leadin(): array {
		return [
			Text::TAG     => 'p',
			Text::CLASSES => [ 'b-hero__leadin', 'h6' ],
			Text::TEXT    => $this->attributes[ Hero_Block::LEAD_IN ] ?? '',
		];
	}

	private function get_headline(): array {
		return [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'b-hero__title', 'h1' ],
			Text::TEXT    => $this->attributes[ Hero_Block::TITLE ] ?? '',
		];
	}

	private function get_text(): array {
		return [
			Text::TAG     => 'div',
			Text::CLASSES => [ 'b-hero__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Hero_Block::DESCRIPTION ] ?? '',
		];
	}
}
