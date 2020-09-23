<?php

namespace Tribe\Project\Templates\Components\blocks\card_grid;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Card_Grid_Controller extends Abstract_Controller {
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const POSTS             = 'posts';
	public const CONTAINER_CLASSES = 'container_classes';
	public const LOOP_CLASSES      = 'loop_classes';
	public const LOOP_ATTRS        = 'loop_attrs';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const LAYOUT            = 'layout';

	private string $layout;
	private string $title;
	private string $description;
	private array  $cta;
	/**
	 * @var Post_List_Object[]
	 */
	private array $posts;
	private array $container_classes;
	private array $loop_classes;
	private array $loop_attrs;
	private array $classes;
	private array $attrs;

	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->posts             = (array) $args[ self::POSTS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->loop_classes      = (array) $args[ self::LOOP_CLASSES ];
		$this->loop_attrs        = (array) $args[ self::LOOP_ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->layout            = (string) $args[ self::LAYOUT ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::POSTS             => [],
			self::CTA               => [],
			self::CONTAINER_CLASSES => [],
			self::LOOP_CLASSES      => [],
			self::LOOP_ATTRS        => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::LAYOUT            => Card_Grid::LAYOUT_STACKED,
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-card-grid__container', 'l-container' ],
			self::LOOP_CLASSES      => [ 'b-card-grid__loop' ],
			self::CLASSES           => [ 'c-block', 'b-card-grid' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_layout(): string {
		return $this->layout;
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'b-card-grid--layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
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
	public function get_loop_classes(): string {
		return Markup_Utils::class_attribute( $this->loop_classes );
	}

	/**
	 * @return string
	 */
	public function get_loop_attrs(): string {
		return Markup_Utils::concat_attrs( $this->loop_attrs );
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
			Content_Block_Controller::LAYOUT  => Content_Block_Controller::LAYOUT_CENTER,
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-card-grid__header'
			],
		];
	}

	public function get_posts_card_args() {
		$cards = [];
		foreach ( $this->posts as $post ) {
			$link    = $post->get_link();
			$cards[] = [
				Card_Controller::TITLE   => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::CONTENT => $post->get_title(),
					]
				),
				Card_Controller::CONTENT => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::CONTENT => $post->get_excerpt(),
					]
				),
				Card_Controller::IMAGE   => $post->get_image_id(),
				Card_Controller::CTA     => defer_template_part(
					'components/link/link',
					null,
					[
						Link_Controller::CONTENT => $link[ 'label' ] ?? $link[ 'url' ],
						Link_Controller::URL     => $link[ 'url' ],
					]
				),
			];
		}

		return $cards;
	}


	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-card-grid__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-card-grid__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_cta(): Deferred_Component {
		$cta = wp_parse_args( $this->cta, [
			'content' => '',
			'url'     => '',
			'target'  => '',
		] );

		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL     => $cta['url'],
			Link_Controller::CONTENT => $cta['content'] ?: $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right'
			],
		] );
	}

}
