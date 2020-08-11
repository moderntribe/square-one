<?php

namespace Tribe\Project\Templates\Components\pagination\single;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var array
	 */
	public $wrapper_classes;

	/**
	 * @var array
	 */
	public $wrapper_attrs;

	/**
	 * @var array
	 */
	protected $list_classes;

	/**
	 * @var array
	 */
	protected $header_classes;

	/**
	 * @var array
	 */
	protected $header_attrs;

	/**
	 * @var array
	 */
	protected $list_attrs;

	/**
	 * @var array
	 */
	protected $item_classes;

	/**
	 * @var array
	 */
	protected $item_attrs;

	/**
	 * @var array
	 */
	protected $container_classes;

	/**
	 * @var array
	 */
	protected $container_attrs;

	/**
	 * @var array
	 */
	public $next_post;

	/**
	 * @var array
	 */
	public $previous_post;

	public function __construct( $args ) {
		$args                    = $this->get_args( $args );
		$this->wrapper_classes   = (array) $args[ 'wrapper_classes' ];
		$this->wrapper_attrs     = (array) $args[ 'wrapper_attrs' ];
		$this->list_classes      = (array) $args[ 'list_classes' ];
		$this->list_attrs        = (array) $args[ 'list_attrs' ];
		$this->item_classes      = (array) $args[ 'item_classes' ];
		$this->item_attrs        = (array) $args[ 'item_attrs' ];
		$this->container_attrs   = (array) $args[ 'container_attrs' ];
		$this->container_classes = (array) $args[ 'container_classes' ];
		$this->header_classes    = (array) $args[ 'header_classes' ];
		$this->header_attrs      = (array) $args[ 'header_attrs' ];
		$this->next_post         = $this->get_next_link();
		$this->previous_post     = $this->get_previous_link();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'wrapper_classes'   => [],
			'wrapper_attrs'     => [],
			'list_classes'      => [],
			'list_attrs'        => [],
			'header_classes'    => [],
			'header_attrs'      => [],
			'item_classes'      => [],
			'item_attrs'        => [],
			'container_attrs'   => [],
			'container_classes' => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'wrapper_classes'   => [ 'pagination', 'pagination--single' ],
			'wrapper_attrs'     => [ 'aria-labelledby' => 'pagination__label-single' ],
			'list_classes'      => [ 'pagination__item', 'pagination__item--next' ],
			'header_classes'    => [ 'visual-hide' ],
			'header_attrs'      => [ 'id' => 'pagination__label-single' ],
			'container_classes' => [ 'pagination__list' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_previous_link(): array {
		$previous = get_adjacent_post( false, '', true );
		if ( empty( $previous ) ) {
			return [];
		}

		return [
			'content' => get_the_title( $previous ),
			'url'     => get_permalink( $previous ),
			'classes' => [],
			'attrs'   => [ 'rel' => 'prev' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_next_link(): array {
		$next = get_adjacent_post( false, '', false );
		if ( empty( $next ) ) {
			return $next;
		}

		return [
			'content' => get_the_title( $next ),
			'url'     => get_permalink( $next ),
			'classes' => [],
			'attrs'   => [ 'rel' => 'next' ],
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
	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	/**
	 * @return string
	 */
	public function wrapper_attrs(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
	}

	/**
	 * @return string
	 */
	public function list_classes(): string {
		return Markup_Utils::class_attribute( $this->list_classes );
	}

	/**
	 * @return string
	 */
	public function list_attrs(): string {
		return Markup_Utils::concat_attrs( $this->list_attrs );
	}

	/**
	 * @return string
	 */
	public function header_classes(): string {
		return Markup_Utils::class_attribute( $this->header_classes );
	}

	/**
	 * @return string
	 */
	public function header_attrs(): string {
		return Markup_Utils::concat_attrs( $this->header_attrs );
	}

	/**
	 * @return string
	 */
	public function item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	/**
	 * @return string
	 */
	public function item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
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

}
