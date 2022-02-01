<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\breadcrumbs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Breadcrumbs_Controller extends Abstract_Controller {

	public const ATTRS        = 'attrs';
	public const BREADCRUMBS  = 'breadcrumbs';
	public const CLASSES      = 'classes';
	public const ITEM_ATTRS   = 'item_attrs';
	public const ITEM_CLASSES = 'item_classes';
	public const LINK_ATTRS   = 'link_attrs';
	public const LINK_CLASSES = 'link_classes';
	public const MAIN_ATTRS   = 'main_attrs';
	public const MAIN_CLASSES = 'main_classes';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	private array $breadcrumbs;

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
	private array $link_attrs;

	/**
	 * @var string[]
	 */
	private array $link_classes;

	/**
	 * @var string[]
	 */
	private array $main_attrs;

	/**
	 * @var string[]
	 */
	private array $main_classes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs        = (array) $args[ self::ATTRS ];
		$this->breadcrumbs  = (array) $args[ self::BREADCRUMBS ];
		$this->classes      = (array) $args[ self::CLASSES ];
		$this->item_attrs   = (array) $args[ self::ITEM_ATTRS ];
		$this->item_classes = (array) $args[ self::ITEM_CLASSES ];
		$this->link_attrs   = (array) $args[ self::LINK_ATTRS ];
		$this->link_classes = (array) $args[ self::LINK_CLASSES ];
		$this->main_attrs   = (array) $args[ self::MAIN_ATTRS ];
		$this->main_classes = (array) $args[ self::MAIN_CLASSES ];
	}

	/**
	 * @return \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	public function get_items(): array {
		return $this->breadcrumbs;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_main_classes(): string {
		return Markup_Utils::class_attribute( $this->main_classes );
	}

	public function get_main_attrs(): string {
		return Markup_Utils::concat_attrs( $this->main_attrs );
	}

	public function get_item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	public function get_item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
	}

	public function get_link_classes(): string {
		return Markup_Utils::class_attribute( $this->link_classes );
	}

	public function get_link_attrs(): string {
		return Markup_Utils::concat_attrs( $this->link_attrs );
	}

	protected function defaults(): array {
		return [
			self::ATTRS        => [],
			self::BREADCRUMBS  => [],
			self::CLASSES      => [],
			self::ITEM_ATTRS   => [],
			self::ITEM_CLASSES => [],
			self::LINK_ATTRS   => [],
			self::LINK_CLASSES => [],
			self::MAIN_ATTRS   => [],
			self::MAIN_CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES      => [ 'c-breadcrumbs' ],
			self::ITEM_CLASSES => [ 'c-breadcrumbs__item' ],
			self::LINK_CLASSES => [ 'c-breadcrumbs__anchor' ],
			self::MAIN_CLASSES => [ 'c-breadcrumbs__list' ],
		];
	}

}
