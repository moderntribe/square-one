<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\links;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Links\Links as Links_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Collections\Link_Collection;

class Links_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const LAYOUT            = 'layout';
	public const LEADIN            = 'leadin';
	public const LINKS             = 'links';
	public const LINKS_TITLE       = 'links_title';
	public const TITLE             = 'title';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	private Cta $cta;
	private Link_Collection $links;
	private string $description;
	private string $layout;
	private string $leadin;
	private string $links_title;
	private string $title;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->links             = $args[ self::LINKS ];
		$this->links_title       = (string) $args[ self::LINKS_TITLE ];
		$this->title             = (string) $args[ self::TITLE ];
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_classes(): string {
		$this->classes[] = 'c-block--layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_header_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => $this->layout === Links_Block::LAYOUT_STACKED ? Content_Block_Controller::LAYOUT_INLINE : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-links__header',
			],
		];
	}

	public function get_links_title_args(): array {
		if ( empty( $this->links_title ) ) {
			return [];
		}

		return [
			Text_Controller::TAG     => 'h3',
			Text_Controller::CLASSES => [ 'b-links__list-title', 'h5' ],
			Text_Controller::CONTENT => $this->links_title,
		];
	}

	public function get_links(): Link_Collection {
		return $this->links;
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::LAYOUT            => Links_Block::LAYOUT_STACKED,
			self::LEADIN            => '',
			self::LINKS             => new Link_Collection(),
			self::LINKS_TITLE       => '',
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-links__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-links__content' ],
			self::CLASSES           => [ 'c-block', 'b-links' ],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-links__leadin',
			],
			Text_Controller::CONTENT => $this->leadin,
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-links__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-links__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description,
		] );
	}

	private function get_cta(): Deferred_Component {
		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL            => $this->cta->link->url,
			Link_Controller::CONTENT        => $this->cta->link->title ?: $this->cta->link->url,
			Link_Controller::TARGET         => $this->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $this->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $this->cta->aria_label,
			Link_Controller::CLASSES        => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

}
