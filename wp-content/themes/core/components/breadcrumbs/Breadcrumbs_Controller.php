<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\breadcrumbs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Breadcrumbs_Controller extends Abstract_Controller {
	/**
	 * @var array
	 */
	private $breadcrumbs;
	/**
	 * @var array
	 */
	private $classes;
	/**
	 * @var array
	 */
	private $attrs;
	/**
	 * @var array
	 */
	private $main_classes;
	/**
	 * @var array
	 */
	private $main_attrs;
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
	private $link_classes;
	/**
	 * @var array
	 */
	private $link_attrs;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->breadcrumbs  = (array) $args['breadcrumbs'];
		$this->classes      = (array) $args['classes'];
		$this->attrs        = (array) $args['attrs'];
		$this->main_classes = (array) $args['main_classes'];
		$this->main_attrs   = (array) $args['main_attrs'];
		$this->item_classes = (array) $args['item_classes'];
		$this->item_attrs   = (array) $args['item_attrs'];
		$this->link_classes = (array) $args['link_classes'];
		$this->link_attrs   = (array) $args['link_attrs'];
	}

	protected function defaults(): array {
		return [
			'breadcrumbs'  => [],
			'classes'      => [],
			'attrs'        => [],
			'main_classes' => [],
			'main_attrs'   => [],
			'item_classes' => [],
			'item_attrs'   => [],
			'link_classes' => [],
			'link_attrs'   => [],
		];
	}

	protected function required(): array {
		return [
			'classes'      => [ 'c-breadcrumbs' ],
			'main_classes' => [ 'c-breadcrumbs__list' ],
			'item_classes' => [ 'c-breadcrumbs__item' ],
			'link_classes' => [ 'c-breadcrumbs__anchor' ],
		];
	}

	/**
	 * @return Breadcrumb[]
	 */
	public function items(): array {
		return $this->breadcrumbs;
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function main_classes(): string {
		return Markup_Utils::class_attribute( $this->main_classes );
	}

	public function main_attrs(): string {
		return Markup_Utils::concat_attrs( $this->main_attrs );
	}

	public function item_classes(): string {
		return Markup_Utils::class_attribute( $this->item_classes );
	}

	public function item_attrs(): string {
		return Markup_Utils::concat_attrs( $this->item_attrs );
	}

	public function link_classes(): string {
		return Markup_Utils::class_attribute( $this->link_classes );
	}

	public function link_attrs(): string {
		return Markup_Utils::concat_attrs( $this->link_attrs );
	}

}
