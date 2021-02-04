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
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;
use Tribe\Project\Theme\Config\Image_Sizes;

class Post_List_Controller extends Abstract_Controller {
	public const POSTS             = 'posts';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	/**
	 * @var Post_List_Object[]
	 */
	private array $posts;
	private array  $container_classes;
	private array  $classes;
	private array  $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

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
			self::POSTS             => [],
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

	public function get_posts_card_args() {
		$cards = [];
		foreach ( $this->posts as $post ) {
			$link    = $post->get_link();
			$uuid    = uniqid( 'p-' );
			$cards[] = [
				Card_Controller::STYLE           => Card_Controller::STYLE_ELEVATED,
				Card_Controller::USE_TARGET_LINK => true,
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
				Card_Controller::DESCRIPTION     => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT => wpautop( $post->get_excerpt() ),
						Container_Controller::CLASSES => [ 't-sink', 's-sink' ],
					],
				),
				Card_Controller::IMAGE           => defer_template_part(
					'components/image/image',
					null,
					[
						Image_Controller::IMG_ID       => $post->get_image_id(),
						Image_Controller::AS_BG        => true,
						Image_Controller::CLASSES      => [ 'c-image--bg', 's-aspect-4-3' ],
						Image_Controller::SRC_SIZE     => Image_Sizes::FOUR_THREE,
						Image_Controller::SRCSET_SIZES => [
							Image_Sizes::FOUR_THREE,
							Image_Sizes::FOUR_THREE_SMALL,
						],
					],
				),
				Card_Controller::CTA             => defer_template_part(
					'components/link/link',
					null,
					[
						Link_Controller::CONTENT => __( 'Read More', 'tribe' ),
						Link_Controller::URL     => $link['url'],
						Link_Controller::CLASSES => [ 'a-cta', 'is-target-link' ],
						Link_Controller::ATTRS   => [
							// These attrs provide the most screen reader accessible link.
							'id'               => $uuid . '-link',
							'aria-labelledby'  => $uuid . '-title',
							'aria-describedby' => $uuid . '-link',
							// Sets this link as the card's click-within target link.
							'data-js'          => 'target-link',
						],
					]
				),
			];
		}

		return $cards;
	}

}
