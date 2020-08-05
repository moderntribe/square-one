<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\breadcrumbs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Controller extends Abstract_Controller {
	/**
	 * @var array
	 */
	private $breadcrumbs;
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
		$this->breadcrumbs     = (array) ( $args['breadcrumbs'] ?? [] );
		$this->wrapper_classes = (array) ( $args['wrapper_classes'] ?? [] );
		$this->wrapper_attrs   = (array) ( $args['wrapper_attrs'] ?? [] );
		$this->main_classes    = (array) ( $args['main_classes'] ?? [] );
		$this->main_attrs      = (array) ( $args['main_attrs'] ?? [] );
		$this->item_classes    = (array) ( $args['item_classes'] ?? [] );
		$this->item_attrs      = (array) ( $args['item_attrs'] ?? [] );
		$this->link_classes    = (array) ( $args['link_classes'] ?? [] );
		$this->link_attrs      = (array) ( $args['link_attrs'] ?? [] );
	}

	/**
	 * @return Breadcrumb[]
	 */
	public function items(): array {
		return $this->breadcrumbs;
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function wrapper_attrs(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
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
