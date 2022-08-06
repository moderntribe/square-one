<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\tribe_query_loop;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Tribe_Query_Loop\Tribe_Query_Loop;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Tribe_Query_Loop_Controller extends Abstract_Controller {

	public const ATTRS        = 'attrs';
	public const CLASSES      = 'classes';
	public const POSTS        = 'posts';
	public const LAYOUT       = 'layout';
	public const HIDE_EXCERPT = 'hide_excerpt';
	public const HIDE_TOPIC   = 'hide_topic';

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
	private array $content_classes;

	/**
	 * @var \WP_Post[]
	 */
	private array $posts;

	private string $layout;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->posts   = (array) $args[ self::POSTS ];
		$this->layout  = (string) $args[ self::LAYOUT ];
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_classes(): string {

		switch ( $this->layout ) {
			case Tribe_Query_Loop::LAYOUT_FEATURE:
				$this->classes[] = 'b-content-loop__featured';
				break;

			case Tribe_Query_Loop::LAYOUT_COLUMNS:
				$this->classes[] = 'b-content-loop__columns';
				break;

			default:
				$this->classes[] = 'b-content-loop__row';
				break;
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_posts(): array {
		return $this->posts;
	}

	public function get_posts_card_args( string $layout = Card_Controller::STYLE_PLAIN ): array {
		$cards = [];
		foreach ( $this->posts as $item ) {
			$link             = [
				'title'  => $item->post_title,
				'url'    => get_the_permalink( $item ),
				'target' => '_self',
			];
			$uuid             = uniqid( 'p-' );
			$cat              = get_the_category( $item->ID );
			$card_description = [];

			$image_array = [
				Image_Controller::IMG_ID       => get_post_thumbnail_id( $item ),
				Image_Controller::AS_BG        => true,
				Image_Controller::CLASSES      => [ 'c-image--bg', 's-aspect-ratio-16-9' ],
				Image_Controller::SRC_SIZE     => Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Controller::SRCSET_SIZES => [
					Image_Sizes::SIXTEEN_NINE_SMALL,
					Image_Sizes::SIXTEEN_NINE,
				],
			];

			$card_cta =
				[
					Link_Controller::CONTENT => __( 'Read More', 'tribe' ),
					Link_Controller::URL     => $link['url'],
					Link_Controller::TARGET  => $link['target'],
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

			// CASE: If not Inline Card Style and is the featured layout

			if ( $layout !== Card_Controller::STYLE_INLINE || $this->layout !== 'layout_feature' ) {
				$card_cta =
					[
						Link_Controller::CONTENT => __( 'Read More', 'tribe' ),
						Link_Controller::URL     => $link['url'],
						Link_Controller::TARGET  => $link['target'],
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

				$card_description = [ Container_Controller::CONTENT => wpautop( $item->post_excerpt ) ];
			}

			$cards[] = [
				Card_Controller::STYLE           => $layout,
				Card_Controller::USE_TARGET_LINK => (bool) $link['url'],
				Card_Controller::META_PRIMARY    => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT => $cat[0]->name ?? '',
						Container_Controller::CLASSES => [ 't-tag' ],
					],
				),
				Card_Controller::TITLE           => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::TAG     => 'h3',
						Text_Controller::CLASSES => [ 'h5' ],
						Text_Controller::CONTENT => $item->post_title,
						// Required for screen reader accessibility, below.
						Text_Controller::ATTRS   => [ 'id' => $uuid . '-title' ],
					]
				),
				Card_Controller::META_SECONDARY  => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT => get_the_date( 'F Y', $item->ID ),
						Container_Controller::CLASSES => [ 'c-card__date' ],
					],
				),
				Card_Controller::DESCRIPTION     => defer_template_part(
					'components/container/container',
					null,
					$card_description,
				),
				Card_Controller::IMAGE           => defer_template_part( 'components/image/image', null, $image_array ),
				Card_Controller::CTA             => defer_template_part( 'components/link/link', null, $card_cta ),
			];
		}

		return $cards;
	}

	public function get_layout(): string {
		return $this->layout;
	}

	public function get_content_classes(): string {
		$this->content_classes[] = 'g-2-up';

		if ( $this->layout === Tribe_Query_Loop::LAYOUT_ROW ) {
			$this->content_classes[] = '';
		}

		elseif ( $this->layout === Tribe_Query_Loop::LAYOUT_COLUMNS ) {
			$this->content_classes[] = 'g-3-up';
		}

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::CLASSES => [],
			self::POSTS   => [],
		];
	}

	protected function required(): array {
		return [];
	}

}
