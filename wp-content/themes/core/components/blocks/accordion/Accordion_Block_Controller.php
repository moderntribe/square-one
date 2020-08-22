<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\accordion;

use PHP_CodeSniffer\Generators\Text;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\accordion\Accordion_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Accordion_Block_Controller extends Abstract_Controller {
	public const ROWS              = 'rows';
	public const HEADER            = 'header';
	public const DESCRIPTION       = 'description';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const LAYOUT            = 'layout';
	public const LAYOUT_INLINE     = 'inline';
	public const LAYOUT_STACKED    = 'stacked';

	private string $layout;
	private array  $rows;
	private string $header;
	private string $description;
	private array  $container_classes;
	private array  $content_classes;
	private array  $classes;
	private array  $attrs;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->rows              = (array) $args[ self::ROWS ];
		$this->header            = (string) $args[ self::HEADER ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => self::LAYOUT_STACKED,
			self::ROWS              => [],
			self::HEADER            => '',
			self::DESCRIPTION       => '',
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-accordion__container', 'l-container' ],
			self::CLASSES           => [ 'c-block', 'b-accordion' ],
			self::CONTENT_CLASSES   => [ 'b-accordion__content' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'c-block--' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		if ( $this->layout === self::LAYOUT_STACKED ) {
			$this->container_classes[] = 'l-sink';
			$this->container_classes[] = 'l-sink--double';
		}

		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return array
	 */
	public function get_header_args(): array {
		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::TITLE   => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => $this->header,
					Text_Controller::TAG     => 'h2',
					Text_Controller::CLASSES => [
						'b-accordion__title',
						'h3',
					],
				]
			),
			Content_Block_Controller::CONTENT => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => $this->description,
					Text_Controller::CLASSES => [
						'b-accordion__description',
						't-sink',
						's-sink',
					],
				]
			),
			Content_Block_Controller::CLASSES => [ 'b-accordion__header' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_content_args(): array {
		return [
			Accordion_Controller::ROWS => $this->rows,
		];
	}

}
