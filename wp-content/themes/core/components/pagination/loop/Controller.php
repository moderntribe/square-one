<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\pagination\loop;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Theme\Pagination_Util;

class Controller extends Abstract_Controller {
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * @var string[]
	 */
	private $list_classes;
	/**
	 * @var string[]
	 */
	private $list_attrs;
	/**
	 * @var string[]
	 */
	private $item_classes;
	/**
	 * @var string[]
	 */
	private $item_attrs;
	/**
	 * @var array
	 */
	private $links;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->classes      = (array) $args['classes'];
		$this->attrs        = (array) $args['attrs'];
		$this->list_classes = (array) $args['list_classes'];
		$this->list_attrs   = (array) $args['list_attrs'];
		$this->item_classes = (array) $args['item_classes'];
		$this->item_attrs   = (array) $args['item_attrs'];
	}

	protected function defaults(): array {
		return [
			'classes'      => [],
			'attrs'        => [],
			'list_classes' => [],
			'list_attrs'   => [],
			'item_classes' => [],
			'item_attrs'   => [],
		];
	}

	protected function required(): array {
		return [
			'classes'      => [ 'c-pagination', 'c-pagination--loop' ],
			'attrs'        => [ 'aria-labelledby' => 'c-pagination__label-single' ],
			'list_classes' => [ 'c-pagination__list' ],
			'item_classes' => [ 'c-pagination__item' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function list_classes(): string {
		return Markup_Utils::class_attribute( $this->list_classes );
	}

	public function list_attrs(): string {
		return Markup_Utils::concat_attrs( $this->list_attrs );
	}

	public function item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	public function item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
	}

	public function links(): array {
		if ( ! isset( $this->links ) ) {
			$this->links = $this->build_links();
		}

		return $this->links;
	}

	public function build_links(): array {
		$pagination = new Pagination_Util();
		$numbers    = $pagination->numbers( 2, true, false, false );

		if ( empty( $numbers ) ) {
			return [];
		}

		return array_map( function ( $number ) {
			$classes = $number['classes'];
			if ( $number['active'] ) {
				$classes[] = 'active';
			}
			if ( $number['prev'] ) {
				$classes[] = 'icon';
				$classes[] = 'icon-cal-arrow-left';
			}
			if ( $number['next'] ) {
				$classes[] = 'icon';
				$classes[] = 'icon-cal-arrow-right';
			}

			return [
				'classes' => $classes,
				'url'     => $number['url'],
				'content' => $number['label'],
			];
		}, $numbers );
	}
}
