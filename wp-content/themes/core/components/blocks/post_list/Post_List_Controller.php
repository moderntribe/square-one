<?php

namespace Tribe\Project\Templates\Components\blocks\post_list;

use PHP_CodeSniffer\Generators\Text;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial as Interstitial_Block;
use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form as Lead_Form_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Post_List_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const POSTS             = 'posts';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private string $layout;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	/**
	 * @var Post_List_Object[]
	 */
	private array $posts;
	private array  $container_classes;
	private array  $classes;
	private array  $attrs;

	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->posts             = (array) $args[ self::POSTS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Interstitial_Block::LAYOUT_LEFT,
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::LEADIN            => '',
			self::POSTS             => [],
			self::CTA               => [],
			self::CONTAINER_CLASSES => [],
			self::CLASSES           => [ 'c-block--full-bleed' ],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-post-list__container', 'l-container' ],
			self::CLASSES           => [ 'c-block', 'b-post-list' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'c-block--' . $this->layout;

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
			Content_Block_Controller::LAYOUT  => $this->layout === Lead_Form_Block::LAYOUT_CENTER ? Content_Block_Controller::LAYOUT_CENTER : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-lead-form__content',
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
	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-post-list__leadin',
			],
			Text_Controller::CONTENT => $this->leadin ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-post-list__title',
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
				'b-post-list__description',
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
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CONTENT => defer_template_part(
				'components/link/link',
				null,
				$this->get_cta_args()
			),
			Container_Controller::TAG     => 'p',
			Container_Controller::CLASSES => [
				'c-block__cta',
				'b-post-list__cta',
			],
		] );
	}


	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->cta, [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return [];
		}

		return [
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::CONTENT => $cta[ 'text' ] ? : $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		];
	}

}
