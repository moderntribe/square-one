<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\accordion;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Accordion\Accordion as Accordion_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Accordion
 */
class Accordion_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const ROWS              = 'rows';
	public const HEADER            = 'header';
	public const DESCRIPTION       = 'description';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTAINER_ATTRS   = 'container_attrs';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	public string $layout;
	public array $rows;
	public string $header;
	public string $description;
	public array $container_classes;
	public array $container_attrs;
	public array $content_classes;
	public array $classes;
	public array $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->get_args( $args );

		$this->layout            = $args[ self::LAYOUT ];
		$this->rows              = $args[ self::ROWS ];
		$this->header            = $args[ self::HEADER ];
		$this->description       = $args[ self::DESCRIPTION ];
		$this->container_classes = $args[ self::CONTAINER_CLASSES ];
		$this->container_attrs   = $args[ self::CONTAINER_ATTRS ];
		$this->content_classes   = $args[ self::CONTENT_CLASSES ];
		$this->classes           = $args[ self::CLASSES ];
		$this->attrs             = $args[ self::ATTRS ];
		$this->init();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Accordion_Block::LAYOUT_STACKED,
			self::ROWS              => [],
			self::HEADER            => '',
			self::DESCRIPTION       => '',
			self::CONTAINER_CLASSES => [],
			self::CONTAINER_ATTRS   => [],
			self::CONTENT_CLASSES   => [ 'b-accordion__content' ],
			self::CLASSES           => [ 'c-block', 'b-accordion' ],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-accordion__container', 'l-container' ],
		];
	}

	/**
	 * Initial setup stuff
	 */
	public function init() {
		$this->classes[] = 'c-block--' . $this->layout;

		if ( $this->layout === 'stacked' ) {
			$this->container_classes[] = 'l-sink';
			$this->container_classes[] = 'l-sink--double';
		}
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	protected function get_args( array $args ): array {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		return $args;
	}

	/**
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	/**
	 * @return string
	 */
	public function content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * Render the header/content-block
	 */
	public function render_header() {
		$args = [
			'title'   => defer_template_part( 'components/text/text', null, [
				'content' => $this->header,
				'tag'     => 'h2',
				'classes' => [ 'b-accordion__title', 'h3' ],
			] ),
			'content' => defer_template_part( 'components/text/text', null, [
				'content' => $this->description,
				'classes' => [ 'b-accordion__description', 't-sink', 's-sink' ],
			] ),
			'classes' => [ 'b-accordion__header' ],
		];
		get_template_part( 'components/content_block/content_block', null, $args );
	}

	/**
	 * Render the content/accordion component
	 */
	public function render_content() {
		$args = [
			'rows' => $this->rows,
		];
		get_template_part( 'components/accordion/accordion', null, $args );
	}

}
