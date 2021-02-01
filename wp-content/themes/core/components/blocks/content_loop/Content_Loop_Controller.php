<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\content_loop;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Content_Loop\Content_Loop as Content_Loop_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Templates\Components\card\Card_Controller;

class Content_Loop_Controller extends Abstract_Controller {

	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const POSTS             = 'posts';
	public const LAYOUT            = 'layout';

	/**
	 * @var string[]
	 */
	private array  $classes;
	private array  $attrs;
	private array  $container_classes;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private array  $content_classes;
	private string $layout;
	

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->posts             = (array) $args[ self::POSTS ];
		$this->layout            = (string) $args[ self::LAYOUT ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::POSTS             => [],
			self::LAYOUT            => Content_Loop_Block::LAYOUT_ROW,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-content-loop' ],
			self::CONTAINER_CLASSES => [ 'l-container' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'b-content-loop--' . $this->layout;

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
	public function get_layout(): string {
		return $this->layout;
	}

	/**
	 * @return array
	 */
	public function get_posts_card_args( string $layout = Card_Controller::STYLE_PLAIN ): array {
		$cards = [];
		foreach ( $this->posts as $post ) {
			$link                = $post->get_link();
			$uuid                = uniqid( 'p-' );
			$cat                 = $post->get_category();
			$card_description    = [];
			$card_cta            = [];

			$image_array = [
				Image_Controller::IMG_ID       => $post->get_image_id(),
				Image_Controller::AS_BG        => true,
				Image_Controller::CLASSES      => [ 'c-image--bg', 's-aspect-ratio-16-9' ],
				Image_Controller::SRC_SIZE     => Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Controller::SRCSET_SIZES => [
					Image_Sizes::SIXTEEN_NINE_SMALL,
					Image_Sizes::SIXTEEN_NINE,
				],
			];

			// CASE: If not Inline Card Style
			if ( $layout !== Card_Controller::STYLE_INLINE ) {
				$card_cta = 
					[
						Link_Controller::CONTENT => __( 'Read More', 'tribe' ),
						Link_Controller::URL     => $link['url'],
						Link_Controller::CLASSES => [
							'c-block__cta-link',
							'a-cta',
						],
						Link_Controller::ATTRS   => [
							// These attrs provide the most screen reader accessible link.
							'id'               => $uuid . '-link',
							'aria-labelledby'  => $uuid . '-title',
							'aria-describedby' => $uuid . '-link',
							// Sets this link as the card's click-within target link.
							'data-js'          => 'target-link',
						],
					];

				$card_description = 
					[
						Container_Controller::CONTENT => wpautop( $post->get_excerpt() ),
						Container_Controller::CLASSES => [ 't-sink', 's-sink' ],
					];
			}

			$cards[] = [
				Card_Controller::STYLE           => $layout,
				Card_Controller::USE_TARGET_LINK => $link['url'] ? true : false,
				Card_Controller::META_PRIMARY    => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT =>  $cat[0]->name ?? '',
						Container_Controller::CLASSES =>  [ 't-tag' ],
					],
				),
				Card_Controller::TITLE           => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::TAG     => 'h3',
						Text_Controller::CLASSES => [ 'h5' ],
						Text_Controller::CONTENT => $post->get_title(),
						// Required for screen reader accessibility, below.
						Text_Controller::ATTRS   => [ 'id' => $uuid . '-title' ],
					]
				),
				Card_Controller::META_SECONDARY  => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT =>  $post->get_post_date() ?? '',
						Container_Controller::CLASSES =>  [ 'c-card__date' ],
					],
				),
				Card_Controller::DESCRIPTION     => defer_template_part(
					'components/container/container',
					null,
					$card_description,
				),
				Card_Controller::IMAGE           => defer_template_part(
					'components/image/image',
					null,
					$image_array,
				),
				Card_Controller::CTA             => defer_template_part(
					'components/link/link',
					null,
					$card_cta,
				),
			];
		}

		return $cards;
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
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-content-loop__header',
			],
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-content-loop__leadin',
				'h6',
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
				'b-content-loop__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	public function get_cta(): Deferred_Component {
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
				'a-cta',
			],
		] );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		$this->content_classes[] = 'g-2-up';

		if ( $this->layout === Content_Loop_Block::LAYOUT_ROW ) {
			$this->content_classes[] = 'g-3-up';
		}
		

		return Markup_Utils::class_attribute( $this->content_classes );
	}
}
