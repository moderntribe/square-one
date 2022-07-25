<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\content_columns;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Content_Columns\Content_Columns;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Collections\Content_Column_Collection;
use Tribe\Project\Templates\Models\Content_Column;

class Content_Columns_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const COLUMNS           = 'columns';
	public const COLUMN_CLASSES    = 'columns_classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_ALIGN     = 'content_align';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const LEADIN            = 'leadin';
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
	private array $column_classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	private Cta $cta;
	private string $content_align;
	private string $description;
	private string $leadin;
	private string $title;
	private Content_Column_Collection $columns;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->column_classes    = (array) $args[ self::COLUMN_CLASSES ];
		$this->columns           = $args[ self::COLUMNS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_align     = (string) $args[ self::CONTENT_ALIGN ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->title             = (string) $args[ self::TITLE ];
	}

	public function get_header_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::LAYOUT  => $this->content_align,
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-content-columns__header',
			],
		];
	}

	public function get_content_args( Content_Column $column ): array {
		$title_tag     = empty( $this->title ) ? 'h2' : 'h3';
		$title_classes = [ count( $this->columns ) === 1 ? 'h3' : 'h4' ];

		return [
			Content_Block_Controller::LAYOUT  => $this->content_align,
			Content_Block_Controller::TITLE   => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => esc_html( $column->col_title ),
					Text_Controller::TAG     => $title_tag,
					Text_Controller::CLASSES => $title_classes,
				]
			),
			Content_Block_Controller::CONTENT => defer_template_part(
				'components/container/container',
				null,
				[
					Container_Controller::CONTENT => wp_kses_post( $column->col_content ),
				]
			),
			Content_Block_Controller::CTA     => defer_template_part(
				'components/link/link',
				null,
				[
					Link_Controller::CONTENT        => $column->cta->link->title,
					Link_Controller::URL            => $column->cta->link->url,
					Link_Controller::TARGET         => $column->cta->link->target,
					Link_Controller::ADD_ARIA_LABEL => $column->cta->add_aria_label,
					Link_Controller::ARIA_LABEL     => $column->cta->aria_label,
					Link_Controller::CLASSES        => [
						'a-btn',
						'a-btn--has-icon-after',
						'icon-arrow-right',
					],
				]
			),
		];
	}

	public function get_classes(): string {
		$this->classes[] = sprintf( 'b-content-columns--count-%d', count( $this->columns ) );

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_columns(): Content_Column_Collection {
		return $this->columns;
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		$this->content_classes[] = 'g-centered';
		$this->content_classes[] = sprintf( 'g-%d-up', count( $this->columns ) );

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_column_classes(): string {
		return Markup_Utils::class_attribute( $this->column_classes );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::COLUMNS           => new Content_Column_Collection(),
			self::COLUMN_CLASSES    => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_ALIGN     => Content_Columns::CONTENT_ALIGN_CENTER,
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::LEADIN            => '',
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-content-columns' ],
			self::COLUMN_CLASSES    => [ 'b-content-columns__column' ],
			self::CONTAINER_CLASSES => [ 'l-container', 'b-content-columns__container' ],
			self::CONTENT_CLASSES   => [ 'b-content-columns__content' ],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-content-columns__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->leadin,
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-content-columns__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-content-columns__description',
			],
			Container_Controller::CONTENT => $this->description,
		] );
	}

	private function get_cta(): ?Deferred_Component {
		if ( ! $this->cta->link->url ) {
			return null;
		}

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
