<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\hero;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Hero
 */
class Hero_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const DESCRIPTION       = 'description';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const CTA               = 'cta';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	public string $layout;
	public int $media;
	public string $description;
	public string $title;
	public string $leadin;
	public array $cta;
	public array $container_classes;
	public array $media_classes;
	public array $content_classes;
	public array $classes;
	public array $attrs;

	/**
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->media             = (int) $args[ self::MEDIA ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'layout'            => 'left',
			'media'             => [],
			'title'             => '',
			'description'       => '',
			'leadin'            => '',
			'cta'               => [],
			'container_classes' => [ 'b-hero__container', 'l-container' ],
			'media_classes'     => [ 'b-hero__media' ],
			'content_classes'   => [ 'b-hero__content' ],
			'classes'           => [
				'c-block',
				'b-hero',
				'c-block--full-bleed',
			],
			'attrs'             => [],
		];
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_content_args(): array {
		return [
			'classes' => [ 'b-hero__content-container', 't-theme--light' ],
			'leadin'  => defer_template_part( 'components/text/text', null, [
				'content' => $this->leadin,
			] ),
			'title'   => defer_template_part( 'components/text/text', null, [
				'content' => $this->title,
			] ),
			'content' => defer_template_part( 'components/container/container', null, [
				'content' => $this->description,
			] ),
			'cta'     => defer_template_part( 'components/link/link', null, $this->cta ?? [] ),
			'layout'  => $this->layout,
		];
	}

	/**
	 * @param $attachment_id
	 *
	 * @return array
	 */
	public function get_image_args(): array {
		if ( empty( $this->media ) ) {
			return [];
		}

		return [
			'attachment'    => Image::factory( (int) $this->media ),
			'as_bg'         => true,
			'use_lazyload'  => true,
			'wrapper_tag'   => 'div',
			'wrapper_class' => [ 'b-hero__figure' ],
			'image_classes' => [ 'b-hero__img', 'c-image__bg' ],
			'src_size'      => Image_Sizes::CORE_FULL,
			'srcset_size'   => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
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
		$this->classes[] = 'c-block--' . $this->layout;

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}
}
