<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Tabs_Block_Controller extends Abstract_Controller {
	private const LAYOUT            = 'layout';
	private const TITLE             = 'title';
	private const DESCRIPTION       = 'description';
	private const TABS              = 'tabs';
	private const CLASSES           = 'classes';
	private const ATTRS             = 'attrs';
	private const CONTAINER_CLASSES = 'container_classes';

	private string $layout;
	private string $title;
	private string $description;
	private array $tabs;
	private array $classes;
	private array $attrs;
	private array $container_classes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = $args[ self::LAYOUT ];
		$this->title             = $args[ self::TITLE ];
		$this->description       = $args[ self::DESCRIPTION ];
		$this->tabs              = $args[ self::TABS ];
		$this->classes           = $args[ self::CLASSES ];
		$this->attrs             = $args[ self::ATTRS ];
		$this->container_classes = $args[ self::CONTAINER_CLASSES ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Tabs_Block::LAYOUT_HORIZONTAL,
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::TABS              => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [ 'l-container' ],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-tabs' ],
			self::CONTAINER_CLASSES => [ 'b-tabs__container' ],
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

	public function get_tabs(): string {
		$args = [
			'tabs'    => $this->tabs,
			'layout'  => $this->layout,
			'classes' => [ 'b-tabs__content' ],
		];

		return tribe_template_part( 'components/tabs/tabs', null, $args );
	}

	private function get_title(): Deferred_Component {
		$args = [
			'tag'     => 'h2',
			'classes' => [ 'b-tabs__title', 'h3' ],
			'content' => $this->title,
		];

		return defer_template_part( 'components/text/text', null, $args );
	}

	private function get_description(): Deferred_Component {
		$args = [
			'classes' => [ 'b-tabs__description', 't-sink', 's-sink' ],
			'content' => $this->description,
		];

		return defer_template_part( 'components/container/container', null, $args );
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
		$this->classes[] = sprintf(  'c-block--layout-%s', $this->layout );
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}

}
