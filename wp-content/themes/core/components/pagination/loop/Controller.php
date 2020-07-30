<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\pagination\loop;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Theme\Pagination_Util;

class Controller {

	/**
	 * @var array
	 */
	private $wrapper_classes;
	/**
	 * @var array
	 */
	private $wrapper_attrs;
	/**
	 * @var array
	 */
	private $list_classes;
	/**
	 * @var array
	 */
	private $list_attrs;
	/**
	 * @var array
	 */
	private $item_classes;
	/**
	 * @var array
	 */
	private $item_attrs;

	/**
	 * @var array
	 */
	private $links;

	public function __construct( array $args = [] ) {
		$this->wrapper_classes = (array) ( $args['wrapper_classes'] ?? [ 'c-pagination', 'c-pagination--loop' ] );
		$this->wrapper_attrs   = (array) ( $args['wrapper_attrs'] ?? [ 'aria-labelledby' => 'c-pagination__label-single' ] );
		$this->list_classes    = (array) ( $args['list_classes'] ?? [
				'g-row',
				'g-row--no-gutters',
				'c-pagination__list',
			] );
		$this->list_attrs      = (array) ( $args['list_attrs'] ?? [] );
		$this->item_classes    = (array) ( $args['item_classes'] ?? [ 'g-col', 'c-pagination__item' ] );
		$this->item_attrs      = (array) ( $args['item_attrs'] ?? [] );
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function wrapper_attrs(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
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

	public static function factory( array $args = [] ): self {
		return new self( $args );
	}
}
