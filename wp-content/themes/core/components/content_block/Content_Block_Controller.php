<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\content_block;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Content_Block_Controller extends Abstract_Controller {

	public const ATTRS          = 'attrs';
	public const CLASSES        = 'classes';
	public const CONTENT        = 'content';
	public const CTA            = 'cta';
	public const LAYOUT         = 'layout';
	public const LAYOUT_CENTER  = 'center';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_LEFT    = 'left';
	public const LAYOUT_STACKED = 'stacked';
	public const LEADIN         = 'leadin';
	public const TAG            = 'tag';
	public const TITLE          = 'title';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $layout;
	private string $tag;

	/**
	 * @uses components/text
	 */
	private ?Deferred_Component $leadin;

	/**
	 * @uses components/text
	 */
	private ?Deferred_Component $title;

	/**
	 * @uses components/container
	 */
	private ?Deferred_Component $content;

	/**
	 * @uses components/link
	 */
	private ?Deferred_Component $cta;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->content = $args[ self::CONTENT ];
		$this->cta     = $args[ self::CTA ];
		$this->layout  = (string) $args[ self::LAYOUT ];
		$this->leadin  = $args[ self::LEADIN ];
		$this->tag     = (string) $args[ self::TAG ];
		$this->title   = $args[ self::TITLE ];
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

	public function render_leadin(): ?Deferred_Component {
		if ( empty( $this->leadin ) ) {
			return null;
		}

		$this->leadin['classes'][] = 'c-content-block__leadin';

		return $this->leadin;
	}

	public function render_title(): ?Deferred_Component {
		if ( empty( $this->title ) ) {
			return null;
		}

		if ( empty( $this->title[ Text_Controller::TAG ] ) ) {
			$this->title[ Text_Controller::TAG ] = 'h2';
		}

		$this->title[ Text_Controller::CLASSES ][] = 'c-content-block__title';

		return $this->title;
	}

	public function render_content(): ?Deferred_Component {
		if ( empty( $this->content ) ) {
			return null;
		}

		$this->content[ Text_Controller::TAG ]       = 'div';
		$this->content[ Text_Controller::CLASSES ][] = 'c-content-block__content';
		$this->content[ Text_Controller::CLASSES ][] = 't-sink';
		$this->content[ Text_Controller::CLASSES ][] = 's-sink';

		return $this->content;
	}

	public function get_cta_args(): array {
		if ( empty( $this->cta[ Link_Controller::URL ] ) ) {
			return [];
		}

		$this->cta[ Link_Controller::CLASSES ][] = 'c-content-block__cta-link';

		return [
			Container_Controller::CLASSES => [ 'c-content-block__cta' ],
			Container_Controller::TAG     => 'p',
			Container_Controller::CONTENT => $this->cta->render(),
		];
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::CLASSES => [],
			self::CONTENT => null,
			self::CTA     => null,
			self::LAYOUT  => self::LAYOUT_LEFT,
			self::LEADIN  => null,
			self::TAG     => 'div',
			self::TITLE   => null,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-content-block' ],
		];
	}

}
