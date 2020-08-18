<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\stats;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Stats\Stats as Stats_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use \Tribe\Project\Templates\Components\statistic\Controller as Statistic;

/**
 * Class Hero
 */
class Stats_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const DISPLAY           = 'display';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const STATS             = 'stats';

	public string $layout;
	public string $display;
	public string $title;
	public string $description;
	public array $container_classes;
	public array $content_classes;
	public array $classes;
	public array $attrs;
	public array $stats;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->display           = (string) $args[ self::DISPLAY ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->stats             = (array) $args[ self::STATS ];
		$this->get_stats();
		$this->init();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Stats_Block::LAYOUT_STACKED,
			self::DISPLAY           => Stats_Block::DISPLAY_DIVIDERS,
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
	 * @return array
	 */
	protected function required(): array {
		return [];
	}

	/**
	 * Initial setup stuff
	 */
	public function init() {
		$this->classes[] = 'c-block--' . $this->layout;
		$this->classes[] = 'c-block--' . $this->display;
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
			'layout'  => $this->layout,
			// 'layout'  => $this->get_content_layout() === Stats_Block::CONTENT_ALIGN_CENTER ? Content_Block::LAYOUT_CENTER : Content_Block::LAYOUT_STACKED,
		];
	}

	private function get_content_layout(): string {
		return $this->attributes[ Stats_Block::CONTENT_ALIGN ] ?? Stats_Block::CONTENT_ALIGN_LEFT;
	}

	/**
	 * @return array
	 */
	private function get_stats(): array {
		$rows = array_filter( $this->stats, function ( $row ) {
			return array_key_exists( Statistic::VALUE, $row );
		} );

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			return [
				Statistic::VALUE => $this->get_statistic_value( $row ),
				Statistic::LABEL => $this->get_statistic_label( $row ),
			];
		}, $rows );
	}

	private function get_statistic_value( $row ): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'content' => $row[ Statistic::VALUE ] ?? '',
		] );
	}

	private function get_statistic_label( $row ): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'content' => $row[ Statistic::LABEL ] ?? '',
		] );
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
