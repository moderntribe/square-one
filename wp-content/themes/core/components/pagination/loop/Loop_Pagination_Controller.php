<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\pagination\loop;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Theme\Pagination_Util;

class Loop_Pagination_Controller extends Abstract_Controller {
	public const CLASSES      = 'classes';
	public const ATTRS        = 'attrs';
	public const LIST_CLASSES = 'list_classes';
	public const LIST_ATTRS   = 'list_attrs';
	public const ITEM_CLASSES = 'item_classes';
	public const ITEM_ATTRS   = 'item_attrs';
	public const LINKS        = 'links';

	private array $classes;
	private array $attrs;
	private array $list_classes;
	private array $list_attrs;
	private array $item_classes;
	private array $item_attrs;
	private array $links;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes      = (array) $args[ self::CLASSES ];
		$this->attrs        = (array) $args[ self::ATTRS ];
		$this->list_classes = (array) $args[ self::LIST_CLASSES ];
		$this->list_attrs   = (array) $args[ self::LIST_ATTRS ];
		$this->item_classes = (array) $args[ self::ITEM_CLASSES ];
		$this->item_attrs   = (array) $args[ self::ITEM_ATTRS ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES      => [],
			self::ATTRS        => [],
			self::LIST_CLASSES => [],
			self::LIST_ATTRS   => [],
			self::ITEM_CLASSES => [],
			self::ITEM_ATTRS   => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES      => [ 'c-pagination', 'c-pagination--loop' ],
			self::ATTRS        => [ 'aria-labelledby' => 'c-pagination__label-single' ],
			self::LIST_CLASSES => [ 'c-pagination__list' ],
			self::ITEM_CLASSES => [ 'c-pagination__item' ],
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

	public function get_item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	public function get_item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
	}

	public function get_links(): array {
		if ( ! isset( $this->links ) ) {
			$this->links = $this->build_links();
		}

		return $this->links;
	}

	private function build_links(): array {
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
				Link_Controller::CLASSES => $classes,
				Link_Controller::URL     => $number['url'],
				Link_Controller::CONTENT => $number['label'],
			];
		}, $numbers );
	}
}
