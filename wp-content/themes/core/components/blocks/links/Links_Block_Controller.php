<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\links;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Links\Links as Links_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\content_block\Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

/**
 * Class Links
 */
class Links_Block_Controller extends Abstract_Controller {

	public const LAYOUT            = 'layout';
	public const DESCRIPTION       = 'description';
	public const TITLE             = 'title';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const LINKS             = 'links';
	public const LINKS_TITLE       = 'links_title';

	private string $layout;
	private string $description;
	private string $title;
	private array $container_classes;
	private array $content_classes;
	private array $classes;
	private array $attrs;
	private array $links;
	private string $links_title;

	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->layout            = $args[ self::LAYOUT ];
		$this->title             = $args[ self::TITLE ];
		$this->description       = $args[ self::DESCRIPTION ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->links             = (array) $args[ self::LINKS ];
		$this->links_title       = $args[ self::LINKS_TITLE ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Links_Block::LAYOUT_STACKED,
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::LINKS             => [],
			self::LINKS_TITLE       => '',
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-links__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-links__content' ],
			self::CLASSES           => [ 'c-block', 'b-links' ],
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
			'tag'     => 'header',
			'classes' => [ 'b-links__header' ],
			'title'   => $this->get_title(),
			'content' => $this->get_description(),
			'layout'  => Controller::LAYOUT_STACKED,
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'tag'     => 'h2',
			'classes' => [ 'b-links__title', 'h3' ],
			'content' => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_description(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-links__description', 't-sink', 's-sink' ],
			'content' => $this->description ?? '',
		] );
	}

	/**
	 * @return array
	 */
	public function get_links_title_args(): array {
		if ( empty( $this->links_title ) ) {
			return [];
		}

		return [
			'tag'     => 'h3',
			'classes' => [ 'b-links__list-title', 'h5' ],
			'content' => $this->links_title,
		];
	}

	/**
	 * @param array $attributes
	 *
	 * @return array
	 */
	public function get_links(): array {
		$rows = array_filter( $this->links, function ( $row ) {
			return array_key_exists( 'item', $row );
		} );

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			return [
				'url'     => $row[ 'item' ][ 'url' ] ?? '',
				'content' => $row[ 'item' ][ 'title' ] ?? $row[ 'item' ][ 'url' ],
				'target'  => $row[ 'item' ][ 'target' ] ?? '',
				'classes' => [ 'b-links__list-link' ],
			];
		}, $rows );
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
		return Markup_Utils::class_attribute( $this->content_classes );
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
		return Markup_Utils::class_attribute( $this->attrs );
	}

}
