<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\accordion;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Accordion\Accordion as Accordion_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Accordion
 */
class Accordion_Block_Controller extends Abstract_Controller {

	/**
	 * @var string
	 */
	public $layout;

	/**
	 * @var array
	 */
	public $rows;

	/**
	 * @var string
	 */
	public $header;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var array
	 */
	public $container_classes;

	/**
	 * @var array
	 */
	public $container_attrs;

	/**
	 * @var array
	 */
	public $content_classes;

	/**
	 * @var array
	 */
	public $classes;

	/**
	 * @var array
	 */
	public $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->get_args( $args );

		$this->layout            = $args[ 'layout' ];
		$this->rows              = $args[ 'rows' ];
		$this->header            = $args[ 'header' ];
		$this->description       = $args[ 'description' ];
		$this->container_classes = $args[ 'container_classes' ];
		$this->container_attrs   = $args[ 'container_attrs' ];
		$this->content_classes   = $args[ 'content_classes' ];
		$this->classes           = $args[ 'classes' ];
		$this->attrs             = $args[ 'attrs' ];
		$this->init();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'layout'            => Accordion_Block::LAYOUT_STACKED,
			'rows'              => [],
			'header'            => '',
			'description'       => '',
			'container_classes' => [],
			'container_attrs'   => [],
			'content_classes'   => [ 'b-accordion__content' ],
			'classes'           => [ 'c-block', 'b-accordion' ],
			'attrs'             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'container_classes' => [ 'b-accordion__container', 'l-container' ],
		];
	}

	/**
	 * Initial setup stuff
	 */
	public function init() {
		$this->classes[] = 'c-block--' . $this->layout;

		if ( $this->layout === 'stacked' ) {
			$this->container_classes[] = 'l-sink';
			$this->container_classes[] = 'l-sink--double';
		}
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
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
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
	public function content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * Render the header/content-block
	 */
	public function render_header() {
		$args = [
			'title'   => defer_template_part( 'components/text/text', null, [
				'content' => $this->header,
				'tag'     => 'h2',
				'classes' => [ 'b-accordion__title', 'h3' ],
			] ),
			'content' => defer_template_part( 'components/text/text', null, [
				'content' => $this->description,
				'classes' => [ 'b-accordion__description', 't-sink', 's-sink' ],
			] ),
			'classes' => [ 'b-accordion__header' ],
		];
		get_template_part( 'components/content_block/content_block', null, $args );
	}

	/**
	 * Render the content/accordion component
	 */
	public function render_content() {
		$args = [
			'rows' => $this->rows,
		];
		get_template_part( 'components/accordion/accordion', null, $args );
	}

}
