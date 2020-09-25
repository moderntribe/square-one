<?php

namespace Tribe\Project\Templates\Components\blocks\content_columns;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Content_Column;

class Content_Columns_Block_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const ATTRS             = 'attrs';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const COLUMNS           = 'columns';
	public const COLUMN_CLASSES    = 'columns_classes';

	private array  $classes;
	private array  $attrs;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private array  $container_classes;
	private array  $content_classes;
	private array  $column_classes;

	/**
	 * @var Content_Column[]
	 */
	private array $columns;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->columns           = (array) $args[ self::COLUMNS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->column_classes    = (array) $args[ self::COLUMN_CLASSES ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::COLUMNS           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::COLUMN_CLASSES    => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-content-columns' ],
			self::CONTAINER_CLASSES => [ 'l-container', 'b-content-columns__container' ],
			self::CONTENT_CLASSES   => [ 'b-content-columns__content' ],
			self::COLUMN_CLASSES    => [ 'b-content-columns__column' ],
		];
	}

	/**
	 * @return array
	 */
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
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-content-columns__header',
			],
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-content-columns__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->leadin ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-content-columns__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-content-columns__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_cta(): Deferred_Component {
		$cta = wp_parse_args( $this->cta, [
			'content' => '',
			'url'     => '',
			'target'  => '',
		] );

		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL     => $cta['url'],
			Link_Controller::CONTENT => $cta['content'] ?: $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right'
			],
		] );
	}

	public function get_content_args( Content_Column $column ) {
		$title_tag     = empty( $this->title ) ? 'h2' : 'h3';
		$title_classes = [ count( $this->columns ) === 1 ? 'h3' : 'h4' ];
		return [
			Content_Block_Controller::TITLE       => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => esc_html( $column->get_title() ),
					Text_Controller::TAG     => $title_tag,
					Text_Controller::CLASSES => $title_classes,
				]
			),
			Content_Block_Controller::CONTENT     => defer_template_part(
				'components/container/container',
				null,
				[
					Container_Controller::CONTENT => wp_kses_post( $column->get_content() ),
				]
			),
			Content_Block_Controller::CTA => defer_template_part(
				'components/link/link',
				null,
				[
					Link_Controller::CONTENT => $column->get_cta()[ Link_Controller::CONTENT ],
					Link_Controller::URL     => $column->get_cta()[ Link_Controller::URL ],
					Link_Controller::TARGET  => $column->get_cta()[ Link_Controller::TARGET ],
					Link_Controller::CLASSES => [
						'a-btn',
						'a-btn--has-icon-after',
						'icon-arrow-right'
					],
				]
			),
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = sprintf( 'b-content-columns--count-%d', count( $this->columns ) );

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_columns() {
		return $this->columns;
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		$this->content_classes[] = 'g-centered';
		$this->content_classes[] = sprintf( 'g-%d-up', count( $this->columns ) );

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return string
	 */
	public function get_column_classes(): string {
		return Markup_Utils::class_attribute( $this->column_classes );
	}
}
