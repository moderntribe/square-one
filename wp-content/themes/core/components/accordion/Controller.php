<?php

namespace Tribe\Project\Templates\Components\accordion;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Accordion_Row;

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
 */
class Controller extends Abstract_Controller {

	/**
	 * @var Accordion_Row[]
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
	public $row_header_attrs;

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
	public $row_content_attrs;

	/**
	 * @var string[]
	 */
	public $row_content_container_classes;

	/**
	 * @var string[]
	 */
	public $row_content_container_attrs;

	/**
	 * @var string
	 */
	public $row_header_name;

	/**
	 * @var string
	 */
	public $row_content_name;

	public function __construct( array $args ) {
		$args                                = $this->get_args( $args );
		$this->rows                          = (array) $args[ 'rows' ];
		$this->container_classes             = (array) $args[ 'container_classes' ];
		$this->container_attrs               = (array) $args[ 'container_attrs' ];
		$this->row_header_tag                = $args[ 'row_header_tag' ];
		$this->row_classes                   = (array) $args[ 'row_classes' ];
		$this->row_header_classes            = (array) $args[ 'row_header_classes' ];
		$this->row_header_attrs              = (array) $args[ 'row_header_attrs' ];
		$this->row_header_container_classes  = (array) $args[ 'row_header_container_classes' ];
		$this->row_content_classes           = (array) $args[ 'row_content_classes' ];
		$this->row_content_attrs             = (array) $args[ 'row_content_attrs' ];
		$this->row_content_container_classes = (array) $args[ 'row_content_container_classes' ];
		$this->row_content_container_attrs   = (array) $args[ 'row_content_container_attrs' ];
		$this->row_header_name               = $args[ 'row_header_name' ];
		$this->row_content_name              = $args[ 'row_content_name' ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'rows'                          => [],
			'container_classes'             => [ 'c-accordion' ],
			'container_attrs'               => [ 'data-js' => 'c-accordion' ],
			'row_header_tag'                => 'h3',
			'row_classes'                   => [ 'c-accordion__row' ],
			'row_header_classes'            => [ 'c-accordion__header', 'h5' ],
			'row_header_attrs'              => [],
			'row_header_container_classes'  => [ 'c-accordion__header-container' ],
			'row_content_classes'           => [ 'c-accordion__content' ],
			'row_content_attrs'             => [],
			'row_content_container_attrs'   => [],
			'row_content_container_classes' => [ 'c-accordion__content-container', 't-sink', 's-sink' ],
			'row_header_name'               => 'title',
			'row_content_name'              => 'row_content',

		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'container_attrs'             => [
				'role'                 => 'tablist',
				'aria-multiselectable' => 'true',
			],
			'row_header_attrs'            => [
				'aria-expanded' => 'false',
				'aria-selected' => 'false',
				'role'          => 'tab',
			],
			'row_content_attrs'           => [
				'hidden'      => 'true',
				'aria-hidden' => 'true',
				'role'        => 'tabpanel',
			],
			'row_content_container_attrs' => [
				'data-depth' => '0',
				'data-autop' => 'true',
			],
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
	 * @param Accordion_Row $row
	 *
	 * @return string
	 */
	public function row_content_attrs( Accordion_Row $row ): string {
		return Markup_Utils::concat_attrs( array_merge( [
			'id'              => esc_attr( $row->content_id ),
			'aria-labelledby' => esc_attr( $row->header_id ),
		], $this->row_content_attrs ) );
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

	/**
	 * @return string
	 */
	public function row_classes(): string {
		return Markup_Utils::class_attribute( $this->row_classes );
	}

	/**
	 * @return string
	 */
	public function row_header_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_classes );
	}

	/**
	 * @return string
	 */
	public function row_content_container_attrs() {
		return Markup_Utils::concat_attrs( $this->row_content_container_attrs );
	}

	/**
	 * @param Accordion_Row row
	 *
	 * @return string
	 */
	public function row_header_attrs( Accordion_Row $row ): string {
		return Markup_Utils::concat_attrs( array_merge( [
			'aria-controls' => esc_attr( $row->content_id ),
			'id'            => esc_attr( $row->header_id ),
		], $this->row_header_attrs ) );
	}

	/**
	 * @return string
	 */
	public function row_header_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_container_classes );
	}

	/**
	 * @return string
	 */
	public function row_content_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_classes );
	}

	/**
	 * @return string
	 */
	public function row_content_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_container_classes );
	}
}
