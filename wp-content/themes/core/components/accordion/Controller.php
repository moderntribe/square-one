<?php

namespace Tribe\Project\Templates\Components\accordion;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Accordion
 *
 * The accordion component is a simple component for title/content row ui's with
 * clickable titles that expand the associated content.
 *
 * This component has these features out of box:
 *  - Full accessibility baked in.
 *  - Lightweight CSS based height animations
 *  - One item open at a time, with scrolling to keep items on screen. Easily switchable in the js.
 *
 * rows take an array if items. Each row item should look like this.
 * [
 *     'header_text' => 'The Row Title',
 *     'header_id' => 'uid-123',
 *     'content_id' => 'uid-123',
 *     'content => 'Amazing accordion content',
 * ]
 */
class Controller extends Abstract_Controller {

	/**
	 * @var string[]s
	 */
	public $rows;

	/**
	 * @var string[]
	 */
	public $container_classes;

	/**
	 * @var string[]
	 */
	public $container_attrs;

	/**
	 * @var string[]
	 */
	public $row_classes;

	/**
	 * @var string
	 */
	public $row_header_tag;

	/**
	 * @var string[]
	 */
	public $row_header_classes;

	/**
	 * @var string[]
	 */
	public $row_header_container_classes;

	/**
	 * @var string[]
	 */
	public $row_content_classes;

	/**
	 * @var string[]
	 */
	public $row_content_container_classes;

	/**
	 * @var string
	 */
	public $row_header_name;

	/**
	 * @var string
	 */
	public $row_content_name;

	public function __construct( array $args ) {
		$args                                = wp_parse_args( $args, $this->defaults() );
		$this->rows                          = (array) $args[ 'rows' ];
		$this->container_classes             = (array) $args[ 'container_classes' ];
		$this->container_attrs               = (array) $args[ 'container_attrs' ];
		$this->row_header_tag                = $args[ 'row_header_tag' ];
		$this->row_classes                   = (array) $args[ 'row_classes' ];
		$this->row_header_classes            = (array) $args[ 'row_header_classes' ];
		$this->row_header_container_classes  = (array) $args[ 'row_header_container_classes' ];
		$this->row_content_classes           = (array) $args[ 'row_content_classes' ];
		$this->row_content_container_classes = (array) $args[ 'row_content_container_classes' ];
		$this->row_header_name               = $args[ 'row_header_name' ];
		$this->row_content_name              = $args[ 'row_content_name' ];
	}

	protected function defaults(): array {
		return [
			'rows'                          => [],
			'container_classes'             => [ 'c-accordion' ],
			'container_attrs'               => [ 'data-js' => 'c-accordion' ],
			'row_header_tag'                => 'h3',
			'row_classes'                   => [ 'c-accordion__row' ],
			'row_header_classes'            => [ 'c-accordion__header', 'h5' ],
			'row_header_container_classes'  => [ 'c-accordion__header-container' ],
			'row_content_classes'           => [ 'c-accordion__content' ],
			'row_content_container_classes' => [ 'c-accordion__content-container', 't-sink', 's-sink' ],
			'row_header_name'               => 'title',
			'row_content_name'              => 'row_content',
		];
	}

	public function container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function container_attrs(): string {
		return Markup_Utils::class_attribute( $this->container_attrs );
	}

	public function row_classes(): string {
		return Markup_Utils::class_attribute( $this->row_classes );
	}

	public function row_header_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_classes );
	}

	public function row_header_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_container_classes );
	}

	public function row_content_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_classes );
	}

	public function row_content_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_container_classes );
	}
}
