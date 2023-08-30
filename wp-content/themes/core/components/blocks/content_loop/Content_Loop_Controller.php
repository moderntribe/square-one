<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\content_loop;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Link;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop as Content_Loop_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Traits\Primary_Term;
use Tribe\Project\Theme\Config\Image_Sizes;

class Content_Loop_Controller extends Abstract_Controller {

	use Primary_Term;

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const ENABLE_PAGINATION = 'enable_pagination';
	public const LAYOUT            = 'layout';
	public const LEADIN            = 'leadin';
	public const POSTS             = 'posts';
	public const TITLE             = 'title';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	private Cta $cta;

	/**
	 * @var \Tribe\Libs\Field_Models\Models\Post_Proxy[]
	 */
	private array $posts;
	private string $description;
	private string $layout;
	private string $leadin;
	private string $title;
	private bool $enable_pagination;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->enable_pagination = (bool) $args[ self::ENABLE_PAGINATION ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->posts             = (array) $args[ self::POSTS ];
		$this->title             = (string) $args[ self::TITLE ];
	}

	public function get_classes(): string {
		$this->classes[] = 'b-content-loop--' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_layout(): string {
		return $this->layout;
	}

	public function is_pagination_enabled(): bool {
		return $this->enable_pagination;
	}

	public function get_posts_card_args( string $layout = Card_Controller::STYLE_PLAIN ): array {
		$cards = [];

		foreach ( $this->posts as $post ) {
			$uuid = uniqid( 'p-' );
			$cat  = $this->get_primary_term( $post->ID );

			// CASE: If not Inline Card Style and is the featured layout
			$show = $layout !== Card_Controller::STYLE_INLINE || $this->layout !== 'layout_feature';

			$cards[] = [
				Card_Controller::STYLE           => $layout,
				Card_Controller::USE_TARGET_LINK => (bool) $post->cta->link->url,
				Card_Controller::META_PRIMARY    => $this->get_card_meta_primary( $cat->name ?? '' ),
				Card_Controller::TITLE           => $this->get_card_title( $post->post_title, $uuid ),
				Card_Controller::META_SECONDARY  => $this->get_card_meta_secondary( (string) get_the_date( 'F Y', $post->post() ) ),
				Card_Controller::DESCRIPTION     => $show ? $this->get_card_description( $post->post_excerpt ) : null,
				Card_Controller::IMAGE           => $this->get_card_image( $post->image->id ),
				Card_Controller::CTA             => $this->get_card_cta( $post->cta->link, $uuid, $show ),
			];
		}

		return $cards;
	}

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

	public function get_cta(): ?Deferred_Component {
		if ( empty( $this->cta->link->url ) ) {
			return null;
		}

		return defer_template_part( 'components/link/link', '', [
			Link_Controller::URL            => $this->cta->link->url,
			Link_Controller::CONTENT        => $this->cta->link->title ?: $this->cta->link->url,
			Link_Controller::TARGET         => $this->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $this->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $this->cta->aria_label,
			Link_Controller::CLASSES        => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

	public function get_content_classes(): string {
		$this->content_classes[] = 'g-2-up';

		if ( $this->layout === Content_Loop_Block::LAYOUT_ROW ) {
			$this->content_classes[] = '';
		}

		elseif ( $this->layout === Content_Loop_Block::LAYOUT_COLUMNS ) {
			$this->content_classes[] = 'g-3-up';
		}

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::ENABLE_PAGINATION => true,
			self::LAYOUT            => Content_Loop_Block::LAYOUT_ROW,
			self::LEADIN            => '',
			self::POSTS             => [],
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-content-loop' ],
			self::CONTAINER_CLASSES => [ 'l-container' ],
		];
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', '', [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-content-loop__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->leadin,
		] );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', '', [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title,
		] );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', '', [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-content-loop__description',
			],
			Container_Controller::CONTENT => $this->description,
		] );
	}

	private function get_card_meta_primary( string $cat_name ): ?Deferred_Component {
		if ( ! $cat_name ) {
			return null;
		}

		return defer_template_part(
			'components/container/container',
			'',
			[
				Container_Controller::CONTENT => $cat_name,
				Container_Controller::CLASSES => [ 't-tag' ],
			],
		);
	}

	private function get_card_title( string $post_title, string $uuid ): Deferred_Component {
		return defer_template_part(
			'components/text/text',
			'',
			[
				Text_Controller::TAG     => 'h3',
				Text_Controller::CLASSES => [ 'h5' ],
				Text_Controller::CONTENT => $post_title,
				// Required for screen reader accessibility, below.
				Text_Controller::ATTRS   => [ 'id' => $uuid . '-title' ],
			]
		);
	}

	private function get_card_meta_secondary( string $date ): Deferred_Component {
		return defer_template_part(
			'components/container/container',
			'',
			[
				Container_Controller::CONTENT => $date,
				Container_Controller::CLASSES => [ 'c-card__date' ],
			],
		);
	}

	private function get_card_description( string $post_excerpt ): Deferred_Component {
		return defer_template_part(
			'components/container/container',
			'',
			[
				Container_Controller::CONTENT => wpautop( $post_excerpt ),
			],
		);
	}

	private function get_card_image( int $post_image_id ): ?Deferred_Component {
		if ( ! $post_image_id ) {
			return null;
		}

		return defer_template_part( 'components/image/image', '', [
			Image_Controller::IMG_ID       => $post_image_id,
			Image_Controller::AS_BG        => true,
			Image_Controller::CLASSES      => [
				'c-image--bg',
				's-aspect-ratio-16-9',
			],
			Image_Controller::SRC_SIZE     => Image_Sizes::SIXTEEN_NINE_SMALL,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
			],
		] );
	}

	private function get_card_cta( Link $post_cta_link, string $uuid, bool $show = false ): ?Deferred_Component {
		if ( ! $post_cta_link->url ) {
			return null;
		}

		$card_cta_args = [
			Link_Controller::CONTENT => esc_html__( 'Read More', 'tribe' ),
			Link_Controller::URL     => $post_cta_link->url,
			Link_Controller::TARGET  => $post_cta_link->target,
			Link_Controller::CLASSES => [
				'u-hidden',
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

		if ( $show ) {
			$card_cta_args[ Link_Controller::CLASSES ] = [
				'c-block__cta-link',
				'a-cta',
			];
		}

		return defer_template_part( 'components/link/link', '', $card_cta_args );
	}

}
