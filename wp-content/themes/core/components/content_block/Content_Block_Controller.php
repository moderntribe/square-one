<?php

namespace Tribe\Project\Templates\Components\content_block;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

/**
 * Class Content_Block
 */
class Content_Block_Controller extends Abstract_Controller {
	public const TAG            = 'tag';
	public const CLASSES        = 'classes';
	public const ATTRS          = 'attrs';
	public const LAYOUT         = 'layout';
	public const LEADIN         = 'leadin';
	public const TITLE          = 'title';
	public const CONTENT        = 'content';
	public const CTA            = 'cta';
	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_STACKED = 'stacked';
	public const LAYOUT_INLINE  = 'inline';

	private string $tag;
	private array  $classes;
	private array  $attrs;
	private string $layout;
	/**
	 * @var Deferred_Component
	 * @uses components/text
	 */
	private Deferred_Component $leadin;
	/**
	 * @var Deferred_Component
	 * @uses components/text
	 */
	private Deferred_Component $title;
	/**
	 * @var Deferred_Component
	 * @uses components/container
	 */
	private Deferred_Component $content;
	/**
	 * @var Deferred_Component
	 * @uses components/link
	 */
	private $cta;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tag     = (string) $args[ self::TAG ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->layout  = (string) $args[ self::LAYOUT ];
		$this->leadin  = $args[ self::LEADIN ];
		$this->title   = $args[ self::TITLE ];
		$this->content = $args[ self::CONTENT ];
		$this->cta     = $args[ self::CTA ];
	}

	protected function defaults(): array {
		return [
			self::TAG     => 'div',
			self::CLASSES => [],
			self::ATTRS   => [],
			self::LAYOUT  => self::LAYOUT_LEFT,
			self::LEADIN  => [],
			self::TITLE   => [],
			self::CONTENT => [],
			self::CTA     => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-content-block' ],
		];
	}

	public function get_tag(): string {
		return tag_escape( $this->tag );
	}

	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-content-block--layout-%s', $this->layout );

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function render_leadin() {
		if ( empty( $this->leadin ) ) {
			return '';
		}

		$this->leadin[ 'classes' ][] = 'c-content-block__leadin';

		return $this->leadin;
	}

	public function render_title() {
		if ( empty( $this->title ) ) {
			return '';
		}

		if ( empty( $this->title[ Text_Controller::TAG ] ) ) {
			$this->title[ Text_Controller::TAG ] = 'h2';
		}

		$this->title[ Text_Controller::CLASSES ][] = 'c-content-block__title';

		return $this->title;
	}

	public function render_content() {
		if ( empty( $this->content ) ) {
			return '';
		}

		$this->content[ Text_Controller::TAG ]       = 'div';
		$this->content[ Text_Controller::CLASSES ][] = 'c-content-block__content';
		$this->content[ Text_Controller::CLASSES ][] = 't-sink';
		$this->content[ Text_Controller::CLASSES ][] = 's-sink';

		return $this->content;
	}

	public function get_cta_args() {
		if ( empty( $this->cta ) ) {
			return '';
		}
		$this->cta[ Link_Controller::CLASSES ][] = 'c-content-block__cta-link';

		return [
			Container_Controller::CLASSES => [ 'c-content-block__cta' ],
			Container_Controller::TAG     => 'p',
			Container_Controller::CONTENT => $this->cta->render(),
		];
	}
}
