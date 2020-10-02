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
class Accordion_Controller extends Abstract_Controller {

	public const ROWS                          = 'rows';
	public const CONTAINER_CLASSES             = 'container_classes';
	public const CONTAINER_ATTRS               = 'container_attrs';
	public const ROW_CLASSES                   = 'row_classes';
	public const ROW_HEADER_TAG                = 'row_header_tag';
	public const ROW_HEADER_ATTRS              = 'row_header_attrs';
	public const ROW_HEADER_CLASSES            = 'row_header_classes';
	public const ROW_HEADER_CONTAINER_CLASSES  = 'row_header_container_classes';
	public const ROW_CONTENT_CLASSES           = 'row_content_classes';
	public const ROW_CONTENT_ATTRS             = 'row_content_attrs';
	public const ROW_CONTENT_CONTAINER_CLASSES = 'row_content_container_classes';
	public const ROW_CONTENT_CONTAINER_ATTRS   = 'row_content_container_attrs';
	public const ROW_HEADER_NAME               = 'row_header_name';
	public const ROW_CONTENT_NAME              = 'row_content_name';
	public const ROW_IDS                       = 'row_ids';

	/**
	 * @var Accordion_Row[]
	 */
	private array  $rows;
	private array  $container_classes;
	private array  $container_attrs;
	private array  $row_classes;
	private string $row_header_tag;
	private array  $row_header_classes;
	private array  $row_header_attrs;
	private array  $row_header_container_classes;
	private array  $row_content_classes;
	private array  $row_content_attrs;
	private array  $row_content_container_classes;
	private array  $row_content_container_attrs;
	private string $row_header_name;
	private string $row_content_name;
	private array  $row_ids;

	public function __construct( array $args = [] ) {
		$args                                = $this->parse_args( $args );
		$this->rows                          = (array) $args[ self::ROWS ];
		$this->container_classes             = (array) $args[ self::CONTAINER_CLASSES ];
		$this->container_attrs               = (array) $args[ self::CONTAINER_ATTRS ];
		$this->row_header_tag                = (string) $args[ self::ROW_HEADER_TAG ];
		$this->row_classes                   = (array) $args[ self::ROW_CLASSES ];
		$this->row_header_classes            = (array) $args[ self::ROW_HEADER_CLASSES ];
		$this->row_header_attrs              = (array) $args[ self::ROW_HEADER_ATTRS ];
		$this->row_header_container_classes  = (array) $args[ self::ROW_HEADER_CONTAINER_CLASSES ];
		$this->row_content_classes           = (array) $args[ self::ROW_CONTENT_CLASSES ];
		$this->row_content_attrs             = (array) $args[ self::ROW_CONTENT_ATTRS ];
		$this->row_content_container_classes = (array) $args[ self::ROW_CONTENT_CONTAINER_CLASSES ];
		$this->row_content_container_attrs   = (array) $args[ self::ROW_CONTENT_CONTAINER_ATTRS ];
		$this->row_header_name               = (string) $args[ self::ROW_HEADER_NAME ];
		$this->row_content_name              = (string) $args[ self::ROW_CONTENT_NAME ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::ROWS                          => [],
			self::CONTAINER_CLASSES             => [],
			self::CONTAINER_ATTRS               => [],
			self::ROW_HEADER_TAG                => 'h3',
			self::ROW_CLASSES                   => [],
			self::ROW_HEADER_CLASSES            => [],
			self::ROW_HEADER_ATTRS              => [],
			self::ROW_HEADER_CONTAINER_CLASSES  => [],
			self::ROW_CONTENT_CLASSES           => [],
			self::ROW_CONTENT_ATTRS             => [],
			self::ROW_CONTENT_CONTAINER_ATTRS   => [],
			self::ROW_CONTENT_CONTAINER_CLASSES => [],
			self::ROW_HEADER_NAME               => 'title',
			self::ROW_CONTENT_NAME              => 'row_content',
			self::ROW_IDS                       => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES             => [ 'c-accordion' ],
			self::ROW_CLASSES                   => [ 'c-accordion__row' ],
			self::ROW_HEADER_CLASSES            => [ 'c-accordion__header', 'h5' ],
			self::ROW_HEADER_CONTAINER_CLASSES  => [ 'c-accordion__header-container' ],
			self::ROW_CONTENT_CLASSES           => [ 'c-accordion__content' ],
			self::ROW_CONTENT_CONTAINER_CLASSES => [ 'c-accordion__content-container', 't-sink', 's-sink' ],
			self::CONTAINER_ATTRS               => [
				'role'                 => 'tablist',
				'aria-multiselectable' => 'true',
				'data-js'              => 'c-accordion',
			],
			self::ROW_HEADER_ATTRS              => [
				'aria-expanded' => 'false',
				'aria-selected' => 'false',
				'role'          => 'tab',
			],
			self::ROW_CONTENT_ATTRS             => [
				'hidden'      => 'true',
				'aria-hidden' => 'true',
				'role'        => 'tabpanel',
			],
			self::ROW_CONTENT_CONTAINER_ATTRS   => [
				'data-depth' => '0',
				'data-autop' => 'true',
			],
		];
	}

	/**
	 * @param Accordion_Row $row
	 *
	 * @return string
	 */
	public function get_row_content_attrs( $row_key ): string {
		return Markup_Utils::concat_attrs( array_merge( [
			'id'              => esc_attr( $this->row_ids[ $row_key ][ 'content_id' ] ?? '' ),
			'aria-labelledby' => esc_attr( $this->row_ids[ $row_key ][ 'header_id' ] ?? '' ),
		], $this->row_content_attrs ) );
	}

	/**
	 * @return array
	 */
	public function get_rows(): array {
		$ids = [];
		foreach ( $this->rows as $row ) {
			$ids[] = [
				'content_id' => uniqid( 'accordion-content-' ),
				'header_id'  => uniqid( 'accordion-header-' ),
			];
		}
		$this->row_ids = $ids;

		return $this->rows;
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	/**
	 * @return string
	 */
	public function get_row_classes(): string {
		return Markup_Utils::class_attribute( $this->row_classes );
	}

	/**
	 * @return string
	 */
	public function get_row_header_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_classes );
	}

	/**
	 * @return string
	 */
	public function get_row_content_container_attrs() {
		return Markup_Utils::concat_attrs( $this->row_content_container_attrs );
	}

	/**
	 * @param Accordion_Row row
	 *
	 * @return string
	 */
	public function get_row_header_attrs( $row_key ): string {
		return Markup_Utils::concat_attrs( array_merge( [
			'aria-controls' => esc_attr( $this->row_ids[ $row_key ][ 'content_id' ] ?? '' ),
			'id'            => esc_attr( $this->row_ids[ $row_key ][ 'header_id' ] ?? '' ),
		], $this->row_header_attrs ) );
	}

	/**
	 * @return string
	 */
	public function get_row_header_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_header_container_classes );
	}

	/**
	 * @return string
	 */
	public function get_row_content_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_classes );
	}

	/**
	 * @return string
	 */
	public function get_row_content_container_classes(): string {
		return Markup_Utils::class_attribute( $this->row_content_container_classes );
	}

	/**
	 * @return string
	 */
	public function get_row_header_tag() {
		return $this->row_header_tag;
	}
}
