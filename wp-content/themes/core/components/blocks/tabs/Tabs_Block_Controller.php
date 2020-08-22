<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\tabs\Tabs_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Tabs_Block_Controller extends Abstract_Controller {
	private const LAYOUT            = 'layout';
	private const TITLE             = 'title';
	private const CONTENT           = 'content';
	private const TABS              = 'tabs';
	private const CLASSES           = 'classes';
	private const ATTRS             = 'attrs';
	private const CONTAINER_CLASSES = 'container_classes';

	private string $layout;
	private string $title;
	private string $content;
	private array  $tabs;
	private array  $classes;
	private array  $attrs;
	private array  $container_classes;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->content           = (string) $args[ self::CONTENT ];
		$this->tabs              = (array) $args[ self::TABS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Tabs_Block::LAYOUT_HORIZONTAL,
			self::TITLE             => '',
			self::CONTENT           => '',
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

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = sprintf(  'c-block--layout-%s', $this->layout );
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return array
	 */
	public function get_header_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::CLASSES => [ 'b-tabs__header' ],
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [ 'b-tabs__title', 'h3' ],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [ 'b-tabs__description', 't-sink', 's-sink' ],
			Container_Controller::CONTENT => $this->content ?? '',
		] );
	}

	/**
	 * @return array
	 */
	public function get_tabs_args(): array {
		return [
			Tabs_Controller::TABS    => $this->tabs,
			Tabs_Controller::LAYOUT  => $this->layout,
			Tabs_Controller::CLASSES => [ 'b-tabs__content' ],
		];
	}
}
