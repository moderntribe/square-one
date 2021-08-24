<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\pagination\loop;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Theme\Pagination_Util;

class Loop_Pagination_Controller extends Abstract_Controller {

	public const ATTRS        = 'attrs';
	public const CLASSES      = 'classes';
	public const ITEM_ATTRS   = 'item_attrs';
	public const ITEM_CLASSES = 'item_classes';
	public const LINKS        = 'links';
	public const LIST_ATTRS   = 'list_attrs';
	public const LIST_CLASSES = 'list_classes';

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
	private array $item_attrs;

	/**
	 * @var string[]
	 */
	private array $item_classes;

	/**
	 * @var string[]
	 */
	private array $links;

	/**
	 * @var string[]
	 */
	private array $list_attrs;

	/**
	 * @var string[]
	 */
	private array $list_classes;

	private Pagination_Util $pagination;

	public function __construct( Pagination_Util $pagination, array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs        = (array) $args[ self::ATTRS ];
		$this->classes      = (array) $args[ self::CLASSES ];
		$this->item_attrs   = (array) $args[ self::ITEM_ATTRS ];
		$this->item_classes = (array) $args[ self::ITEM_CLASSES ];
		$this->list_attrs   = (array) $args[ self::LIST_ATTRS ];
		$this->list_classes = (array) $args[ self::LIST_CLASSES ];
		$this->pagination   = $pagination;
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

	protected function defaults(): array {
		return [
			self::ATTRS        => [],
			self::CLASSES      => [],
			self::ITEM_ATTRS   => [],
			self::ITEM_CLASSES => [],
			self::LIST_ATTRS   => [],
			self::LIST_CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::ATTRS        => [ 'aria-label' => esc_attr__( 'Loop Pagination', 'tribe' ) ],
			self::CLASSES      => [ 'c-pagination', 'c-pagination--loop' ],
			self::ITEM_CLASSES => [ 'c-pagination__item' ],
			self::LIST_CLASSES => [ 'c-pagination__list' ],
		];
	}

	private function build_links(): array {
		$numbers = $this->pagination->numbers( 2, true, false, false );

		if ( empty( $numbers ) ) {
			return [];
		}

		return array_map( static function ( $number ) {
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
