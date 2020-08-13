<?php

namespace Tribe\Project\Templates\Components\content_block;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

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
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * @var string
	 */
	private $layout;
	/**
	 * @var Deferred_Component
	 * @uses components/text
	 */
	private $leadin;
	/**
	 * @var Deferred_Component
	 * @uses components/text
	 */
	private $title;
	/**
	 * @var Deferred_Component
	 * @uses components/container
	 */
	private $content;
	/**
	 * @var Deferred_Component
	 * @uses components/link
	 */
	private $cta;

	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		$this->tag     = $args['tag'];
		$this->classes = (array) $args['classes'];
		$this->attrs   = (array) $args['attrs'];
		$this->layout  = $args['layout'];
		$this->leadin  = $args['leadin'];
		$this->title   = $args['title'];
		$this->content = $args['content'];
		$this->cta     = $args['cta'];
	}

	protected function defaults(): array {
		return [
			'tag'     => 'div',
			'classes' => [],
			'attrs'   => [],
			'layout'  => self::LAYOUT_LEFT,
			'leadin'  => null,
			'title'   => null,
			'content' => null,
			'cta'     => null,
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-content-block', 'c-content-block--layout-' . $this->layout ],
		];
	}

	public function tag(): string {
		return tag_escape( $this->tag );
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function render_leadin() {
		$this->leadin['classes'][] = 'c-content-block__leadin';

		return $this->leadin;
	}

	public function render_title() {
		if ( empty( $this->title['tag'] ) ) {
			$this->title['tag'] = 'h2';
		}

		$this->title['classes'][] = 'c-content-block__title';

		return $this->title;
	}

	public function render_content() {
		$this->content['tag']       = 'div';
		$this->content['classes'][] = 'c-content-block__content';
		$this->content['classes'][] = 't-sink';
		$this->content['classes'][] = 's-sink';

		return $this->content;
	}

	public function render_cta() {
		$this->cta['classes'][]                = 'c-content-block__cta-link';
		$this->cta['wrapper_tag']              = 'p';
		$this->cta['wrapper_tag']['classes'][] = 'c-content-block__cta';

		return $this->cta;
	}
}
