<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\stats;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Stats\Stats as Stats_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use \Tribe\Project\Templates\Components\statistic\Statistic_Controller as Statistic;
use \Tribe\Project\Templates\Components\content_block\Controller as Content_Block;

/**
 * Class Hero
 */
class Stats_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const DISPLAY_DIVIDERS  = 'display_dividers';
	public const CONTENT_ALIGN     = 'content_align';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const STATS             = 'stats';

	public string $layout;
	public string $content_align;
	public string $display_dividers;
	public string $title;
	public string $description;
	public array $container_classes;
	public array $content_classes;
	public array $classes;
	public array $attrs;
	public array $stats;

	public function __construct( array $args = [] ) {
		$args = $this->parse_parts( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->content_align     = (string) $args[ self::CONTENT_ALIGN ];
		$this->display_dividers  = (string) $args[ self::DISPLAY_DIVIDERS ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->stats             = (array) $args[ self::STATS ];
		$this->init();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Stats_Block::LAYOUT_STACKED,
			self::CONTENT_ALIGN     => Stats_Block::CONTENT_ALIGN_LEFT,
			self::DISPLAY_DIVIDERS  => Stats_Block::DISPLAY_DIVIDERS,
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::CONTAINER_CLASSES => [ 'b-stats__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-stats__content' ],
			self::CLASSES           => [ 'c-block', 'b-stats' ],
			self::ATTRS             => [],
			self::STATS             => [],
		];
	}

	/**
	 * Initial setup stuff
	 */
	public function init() {
		$this->classes[] = 'c-block--' . $this->layout;
		$this->classes[] = 'c-block--' . $this->display_dividers;
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_content_args(): array {
		return [
			'tag'     => 'header',
			'classes' => [ 'b-stats__header' ],
			'title'   => defer_template_part( 'components/text/text', null, [
				'content' => $this->title,
				'tag'     => 'h2',
				'classes' => [ 'b-stats__title', 'h3' ],
			] ),
			'content' => defer_template_part( 'components/text/text', null, [
				'content' => $this->description,
				'classes' => [ 'b-stats__description', 't-sink', 's-sink' ],
			] ),
			'layout'  => Stats_Block::CONTENT_ALIGN_CENTER === $this->content_align ? Content_Block::LAYOUT_CENTER : Content_Block::LAYOUT_STACKED,
		];
	}

	/**
	 * @return array
	 */
	public function get_stats(): array {
		$statistic_args = [];

		foreach ( $this->stats as $item ) {
			// Skip over statistic rows with no value.
			if ( empty( $item[ Stats_Block::ROW_VALUE ] ) ) {
				continue;
			}

			$statistic_args[] = [
				Statistic::VALUE => defer_template_part( 'components/text/text', null, $this->get_value_args( $item ) ),
				Statistic::LABEL => defer_template_part( 'components/text/text', null, $this->get_label_args( $item ) ),
			];
		}

		return $statistic_args;
	}

	/**
	 * @return array
	 */
	protected function get_value_args( $item ): array {
		return [
			'content' => esc_html( $item[ Stats_Block::ROW_VALUE ] ),
		];
	}

	/**
	 * @return array
	 */
	protected function get_label_args( $item ): array {
		return [
			'content' => esc_html( $item[ Stats_Block::ROW_LABEL ] ),
		];
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
	public function content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
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
}
