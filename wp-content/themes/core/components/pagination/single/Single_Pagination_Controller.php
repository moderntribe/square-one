<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\pagination\single;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Single_Pagination_Controller extends Abstract_Controller {

	public const ATTRS               = 'attrs';
	public const CLASSES             = 'classes';
	public const CONTAINER_ATTRS     = 'container_attrs';
	public const CONTAINER_CLASSES   = 'container_classes';
	public const HEADER_ATTRS        = 'header_attrs';
	public const HEADER_CLASSES      = 'header_classes';
	public const ITEM_ATTRS          = 'item_attrs';
	public const ITEM_CLASSES        = 'item_classes';
	public const LIST_ATTRS          = 'list_attrs';
	public const LIST_CLASSES        = 'list_classes';
	public const NEXT_LINK_LABEL     = 'next_link_label';
	public const PREVIOUS_LINK_LABEL = 'previous_link_label';

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
	private array $container_attrs;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $header_attrs;

	/**
	 * @var string[]
	 */
	private array $header_classes;

	/**
	 * @var string[]
	 */
	private array $item_attrs;

	/**
	 * @var string[]
	 */
	private array $item_classes;

	/**
	 * @var string[]
	 */
	private array $list_attrs;

	/**
	 * @var string[]
	 */
	private array $list_classes;
	private string $next_link_label;
	private string $previous_link_label;

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->get_args( $args );

		$this->attrs               = (array) $args[ self::ATTRS ];
		$this->classes             = (array) $args[ self::CLASSES ];
		$this->container_attrs     = (array) $args[ self::CONTAINER_ATTRS ];
		$this->container_classes   = (array) $args[ self::CONTAINER_CLASSES ];
		$this->header_attrs        = (array) $args[ self::HEADER_ATTRS ];
		$this->header_classes      = (array) $args[ self::HEADER_CLASSES ];
		$this->item_attrs          = (array) $args[ self::ITEM_ATTRS ];
		$this->item_classes        = (array) $args[ self::ITEM_CLASSES ];
		$this->list_attrs          = (array) $args[ self::LIST_ATTRS ];
		$this->list_classes        = (array) $args[ self::LIST_CLASSES ];
		$this->next_link_label     = (string) $args[ self::NEXT_LINK_LABEL ];
		$this->previous_link_label = (string) $args[ self::PREVIOUS_LINK_LABEL ];
	}

	public function get_previous_link_args(): array {
		$previous = get_adjacent_post( false, '', true );

		if ( empty( $previous ) ) {
			return [];
		}

		return [
			Link_Controller::CONTENT => ( empty( $this->previous_link_label ) ? get_the_title( $previous ) : $this->previous_link_label ),
			Link_Controller::URL     => get_permalink( $previous ),
			Link_Controller::CLASSES => [ 'pagination__item-link--previous' ],
			Link_Controller::ATTRS   => [ 'rel' => 'prev' ],
		];
	}

	public function get_next_link_args(): array {
		$next = get_adjacent_post( false, '', false );

		if ( empty( $next ) ) {
			return [];
		}

		return [
			Link_Controller::CONTENT => ( empty( $this->next_link_label ) ? get_the_title( $next ) : $this->next_link_label ),
			Link_Controller::URL     => get_permalink( $next ),
			Link_Controller::CLASSES => ['pagination__item-link--next'],
			Link_Controller::ATTRS   => [ 'rel' => 'next' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_list_classes(): string {
		return Markup_Utils::class_attribute( $this->list_classes );
	}

	public function get_list_attrs(): string {
		return Markup_Utils::concat_attrs( $this->list_attrs );
	}

	public function get_header_classes(): string {
		return Markup_Utils::class_attribute( $this->header_classes );
	}

	public function get_header_attrs(): string {
		return Markup_Utils::concat_attrs( $this->header_attrs );
	}

	public function get_item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	public function get_item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	protected function defaults(): array {
		return [
			self::ATTRS               => [],
			self::CLASSES             => [],
			self::CONTAINER_ATTRS     => [],
			self::CONTAINER_CLASSES   => [],
			self::HEADER_ATTRS        => [],
			self::HEADER_CLASSES      => [],
			self::ITEM_ATTRS          => [],
			self::ITEM_CLASSES        => [],
			self::LIST_ATTRS          => [],
			self::LIST_CLASSES        => [],
			self::NEXT_LINK_LABEL     => '',
			self::PREVIOUS_LINK_LABEL => '',
		];
	}

	protected function required(): array {
		return [
			self::ATTRS             => [ 'aria-label' => esc_attr__( 'Post Pagination', 'tribe' ) ],
			self::CLASSES           => [ 'pagination', 'pagination--single' ],
			self::CONTAINER_CLASSES => [ 'pagination__list' ],
			self::HEADER_ATTRS      => [ 'id' => 'pagination__label-single' ],
			self::HEADER_CLASSES    => [ 'visual-hide' ],
			self::LIST_CLASSES      => [ 'pagination__item' ],
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

}
