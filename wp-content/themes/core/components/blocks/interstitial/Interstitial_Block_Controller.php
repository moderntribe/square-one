<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\interstitial;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Hero\Model;
use Tribe\Project\Blocks\Types\Interstitial\Controller;
use Tribe\Project\Blocks\Types\Interstitial\Interstitial as Interstitial_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Interstitial
 */
class Interstitial_Block_Controller extends Abstract_Controller {

	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const CONTENT           = 'content';
	public const CTA               = 'cta';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	public string $layout;
	public ?int $media;
	public string $content;
	public array $cta;
	public array $container_classes;
	public array $media_classes;
	public array $content_classes;
	public array $classes;
	public array $attrs;

	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->layout            = $args[ self::LAYOUT ];
		$this->media             = $args[ self::MEDIA ];
		$this->content           = $args[ self::CONTENT ];
		$this->cta               = $args[ self::CTA ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->init();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Interstitial_Block::LAYOUT_LEFT,
			self::MEDIA             => null,
			self::CONTENT           => '',
			self::CTA               => '',
			self::CONTAINER_CLASSES => [ 'b-interstitial__container', 'l-container' ],
			self::MEDIA_CLASSES     => [ 'b-interstitial__media' ],
			self::CONTENT_CLASSES   => [ 'b-interstitial__content' ],
			self::CLASSES           => [ 'c-block', 'b-interstitial', 'c-block--full-bleed' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->classes[] = 'c-block--' . $this->layout;
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
	public function media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	/**
	 * @return string
	 */
	public function content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
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

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_content(): array {
		if ( empty( $this->content ) && empty( $this->cta ) ) {
			return [];
		}

		return [
			'classes' => [ 'b-interstitial__content-container', 't-theme--light' ],
			'title'   => defer_template_part(
				'components/text/text',
				null,
				$this->get_headline_args()
			),
			'cta'     => defer_template_part( 'components/container/container', null, $this->get_cta_args() ),
			'layout'  => $this->layout,
		];
	}

	/**
	 * @return array
	 */
	protected function get_headline_args(): array {
		return [
			'tag'     => 'h2',
			'classes' => [ 'b-interstitial__title', 'h3' ],
			'content' => esc_html( $this->content ),
		];
	}

	protected function get_cta_args(): array {

		$cta = wp_parse_args( $this->cta, [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return [];
		}

		$cta_args       = [
			'url'     => $cta[ 'url' ],
			'content' => $cta[ 'text' ] ? : $cta[ 'url' ],
			'target'  => $cta[ 'target' ],
			'classes' => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
		];
		$container_args = [
			'content' => defer_template_part( 'component/link/link', null, $cta_args ),
			'tag'     => 'p',
			'classes' => [ 'b-interstitial__cta' ],
		];

		return $container_args;
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_media_args(): array {
		if ( ! $this->media ) {
			return [];
		}

		return $this->get_image( $this->media );
	}

	/**
	 * @param $attachment_id
	 *
	 * @return array
	 */
	protected function get_image( $attachment_id ): array {
		return [
			'attachment'    => Image::factory( (int) $attachment_id ),
			'as_bg'         => true,
			'use_lazyload'  => true,
			'wrapper_tag'   => 'div',
			'wrapper_class' => [ 'b-interstitial__figure' ],
			'image_classes' => [ 'b-interstitial__img', 'c-image__bg' ],
			'src_size'      => Image_Sizes::CORE_FULL,
			'srcset_size'   => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}
}
