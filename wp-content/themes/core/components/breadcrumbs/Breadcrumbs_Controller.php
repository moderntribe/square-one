<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\breadcrumbs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Breadcrumbs_Controller extends Abstract_Controller {
	public const BREADCRUMBS  = 'breadcrumbs';
	public const CLASSES      = 'classes';
	public const ATTRS        = 'attrs';
	public const MAIN_CLASSES = 'main_classes';
	public const MAIN_ATTRS   = 'main_attrs';
	public const ITEM_CLASSES = 'item_classes';
	public const ITEM_ATTRS   = 'item_attrs';
	public const LINK_CLASSES = 'link_classes';
	public const LINK_ATTRS   = 'link_attrs';

	private array $breadcrumbs;
	private array $classes;
	private array $attrs;
	private array $main_classes;
	private array $main_attrs;
	private array $item_classes;
	private array $item_attrs;
	private array $link_classes;
	private array $link_attrs;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->breadcrumbs  = (array) $args[ 'breadcrumbs' ];
		$this->classes      = (array) $args[ 'classes' ];
		$this->attrs        = (array) $args[ 'attrs' ];
		$this->main_classes = (array) $args[ 'main_classes' ];
		$this->main_attrs   = (array) $args[ 'main_attrs' ];
		$this->item_classes = (array) $args[ 'item_classes' ];
		$this->item_attrs   = (array) $args[ 'item_attrs' ];
		$this->link_classes = (array) $args[ 'link_classes' ];
		$this->link_attrs   = (array) $args[ 'link_attrs' ];
	}

	protected function defaults(): array {
		return [
			self::BREADCRUMBS  => [],
			self::CLASSES      => [],
			self::ATTRS        => [],
			self::MAIN_CLASSES => [],
			self::MAIN_ATTRS   => [],
			self::ITEM_CLASSES => [],
			self::ITEM_ATTRS   => [],
			self::LINK_ATTRS   => [],
			self::LINK_CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES       => [ 'c-breadcrumbs' ],
			self::MAIN_CLASSES => [ 'c-breadcrumbs__list' ],
			self::ITEM_CLASSES  => [ 'c-breadcrumbs__item' ],
			self::LINK_CLASSES  => [ 'c-breadcrumbs__anchor' ],
		];
	}

	/**
	 * @return Breadcrumb[]
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

}
