<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Controller extends Abstract_Controller {

	public $layout;
	public $title;
	public $description;
	public $tabs;
	public $classes;
	public $attrs;
	public $container_classes;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->default() );

		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		$this->layout            = $args['layout'];
		$this->title             = $args['title'];
		$this->description       = $args['description'];
		$this->tabs              = (array) $args['tabs'];
		$this->classes           = (array) $args['classes'];
		$this->attrs             = (array) $args['attrs'];
		$this->container_classes = (array) $args['container_classes'];
	}

	/**
	 * @return array
	 */
	protected function default(): array {
		return [
			'layout'            => Tabs_Block::LAYOUT_HORIZONTAL,
			'title'             => '',
			'description'       => '',
			'tabs'              => [],
			'classes'           => [],
			'attrs'             => [],
			'container_classes' => 'l-container',
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'classes'           => [ 'c-block', 'b-tabs', 'c-block--' . $this->layout ],
			'container_classes' => [ 'b-tabs__container' ],
		];
	}

	public function get_header(): string {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return '';
		}

		$args = [
			'tag'     => 'header',
			'classes' => [ 'b-tabs__header' ],
			'title'   => $this->get_title(),
			'content' => $this->get_description(),
		];

		return tribe_template_part( 'components/content_block/content_block', null, $args );
	}

	public function get_tabs(): array {
		return [];
	}

	private function get_title(): Deferred_Component {
		$args = [
			'tag'     => 'h2',
			'classes' => [ 'b-tabs__title', 'h2' ],
			'content' => $this->title,
		];

		return defer_template_part( 'component/text/text', null, $args );
	}

	private function get_description(): Deferred_Component {
		$args = [
			'classes' => [ 'b-tabs__description', 't-sink', 's-sink' ],
			'content' => $this->title,
		];

		return defer_template_part( 'component/text/text', null, $args );
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
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}

}
