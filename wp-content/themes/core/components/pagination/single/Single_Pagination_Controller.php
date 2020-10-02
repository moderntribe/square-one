<?php

namespace Tribe\Project\Templates\Components\pagination\single;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Single_Pagination_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const LIST_CLASSES      = 'list_classes';
	public const HEADER_CLASSES    = 'header_classes';
	public const HEADER_ATTRS      = 'header_attrs';
	public const LIST_ATTRS        = 'list_attrs';
	public const ITEM_CLASSES      = 'item_classes';
	public const ITEM_ATTRS        = 'item_attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTAINER_ATTRS   = 'container_attrs';
	public const NEXT_POST         = 'next_post';
	public const PREVIOUS_POST     = 'previous_post';

	private array $classes;
	private array $attrs;
	private array $list_classes;
	private array $header_classes;
	private array $header_attrs;
	private array $list_attrs;
	private array $item_classes;
	private array $item_attrs;
	private array $container_classes;
	private array $container_attrs;
	private array $next_post;
	private array $previous_post;

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->get_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->list_classes      = (array) $args[ self::LIST_CLASSES ];
		$this->list_attrs        = (array) $args[ self::LIST_ATTRS ];
		$this->item_classes      = (array) $args[ self::ITEM_CLASSES ];
		$this->item_attrs        = (array) $args[ self::ITEM_ATTRS ];
		$this->container_attrs   = (array) $args[ self::CONTAINER_ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->header_classes    = (array) $args[ self::HEADER_CLASSES ];
		$this->header_attrs      = (array) $args[ self::HEADER_ATTRS ];
		$this->next_post         = $this->get_next_link_args();
		$this->previous_post     = $this->get_previous_link_args();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::LIST_CLASSES      => [],
			self::ITEM_CLASSES      => [],
			self::ITEM_ATTRS        => [],
			self::LIST_ATTRS        => [],
			self::CONTAINER_ATTRS   => [],
			self::CONTAINER_CLASSES => [],
			self::HEADER_CLASSES    => [],
			self::HEADER_ATTRS      => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES           => [ 'pagination', 'pagination--single' ],
			self::ATTRS             => [ 'aria-label' => esc_attr__( 'Post Pagination', 'tribe' ) ],
			self::LIST_CLASSES      => [ 'pagination__item', 'pagination__item--next' ],
			self::HEADER_CLASSES    => [ 'visual-hide' ],
			self::HEADER_ATTRS      => [ 'id' => 'pagination__label-single' ],
			self::CONTAINER_CLASSES => [ 'pagination__list' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_previous_link_args(): array {
		$previous = get_adjacent_post( false, '', true );

		if ( empty( $previous ) ) {
			return [];
		}

		return [
			Link_Controller::CONTENT => get_the_title( $previous ),
			Link_Controller::URL     => get_permalink( $previous ),
			Link_Controller::CLASSES => [],
			Link_Controller::ATTRS   => [ 'rel' => 'prev' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_next_link_args(): array {
		$next = get_adjacent_post( false, '', false );

		if ( empty( $next ) ) {
			return [];
		}

		return [
			Link_Controller::CONTENT => get_the_title( $next ),
			Link_Controller::URL     => get_permalink( $next ),
			Link_Controller::CLASSES => [],
			Link_Controller::ATTRS   => [ 'rel' => 'next' ],
		];
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
	public function get_classes(): string {
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
	public function get_list_classes(): string {
		return Markup_Utils::class_attribute( $this->list_classes );
	}

	/**
	 * @return string
	 */
	public function get_list_attrs(): string {
		return Markup_Utils::concat_attrs( $this->list_attrs );
	}

	/**
	 * @return string
	 */
	public function get_header_classes(): string {
		return Markup_Utils::class_attribute( $this->header_classes );
	}

	/**
	 * @return string
	 */
	public function get_header_attrs(): string {
		return Markup_Utils::concat_attrs( $this->header_attrs );
	}

	/**
	 * @return string
	 */
	public function get_item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	/**
	 * @return string
	 */
	public function get_item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
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
	public function get_container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

}
