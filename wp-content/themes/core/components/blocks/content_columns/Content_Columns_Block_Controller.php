<?php

namespace Tribe\Project\Templates\Components\blocks\content_columns;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Content_Column;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Content_Columns_Block_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const ATTRS             = 'attrs';
	public const COLUMNS           = 'columns';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const LAYOUT            = 'layout';

	private array $classes;
	private array $attrs;
	private array $container_classes;
	private string $title;
	private string $description;
	private array $cta;
	private string $layout;
	/**
	 * @var Content_Column[]
	 */
	private array $columns;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->columns           = (array) $args[ self::COLUMNS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::COLUMNS           => [],
			self::CONTAINER_CLASSES => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::LAYOUT            => Content_Columns::LAYOUT_LEFT,
		];
	}

	/**
	 * @return array
	 */
	public function get_header_content(): array {
		if ( empty( $this->title ) && empty( $this->description ) && empty( $this->cta ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TITLE   => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => $this->title,
					Text_Controller::TAG     => 'h2',
					Text_Controller::CLASSES => [ 'h2' ],
				]
			),
			Content_Block_Controller::CONTENT => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => $this->description,
				]
			),
			Content_Block_Controller::CTA     => defer_template_part(
				'components/link/link',
				null,
				[
					Link_Controller::CLASSES => [
						'c-block__cta-link',
						'a-btn',
						'a-btn--has-icon-after',
						'icon-arrow-right',
					],
					Link_Controller::CONTENT => $this->cta[ Link_Controller::CONTENT ],
					Link_Controller::URL     => $this->cta[ Link_Controller::URL ],
					Link_Controller::TARGET  => $this->cta[ Link_Controller::TARGET ],
				]
			),
		];
	}


	/**
	 * @param Content_Column $column
	 *
	 * @return array
	 */
	public function get_column_content_args( Content_Column $column ): array {

		return [
			Content_Block_Controller::TITLE   => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => esc_html( $column->get_title() ),
					Text_Controller::TAG     => 'h3',
					Text_Controller::CLASSES => [ 'h3' ],
				]
			),
			Content_Block_Controller::CONTENT => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => wp_kses_post( $column->get_content() ),
				]
			),
			Content_Block_Controller::CTA     => defer_template_part(
				'components/link/link',
				null,
				[
					Link_Controller::CLASSES => [
						'c-block__cta-link',
						'a-btn',
						'a-btn--has-icon-after',
						'icon-arrow-right',
					],
					Link_Controller::CONTENT => $column->get_cta()[ Link_Controller::CONTENT ],
					Link_Controller::URL     => $column->get_cta()[ Link_Controller::URL ],
					Link_Controller::TARGET  => $column->get_cta()[ Link_Controller::TARGET ],
				]
			),
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'c-block--layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return array|Content_Column[]
	 */
	public function get_columns(): array {
		return $this->columns;
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}
}
