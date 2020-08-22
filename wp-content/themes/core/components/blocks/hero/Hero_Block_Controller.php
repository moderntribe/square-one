<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\hero;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Image\Image_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Hero_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private string $layout;
	private int    $media;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private array  $container_classes;
	private array  $media_classes;
	private array  $content_classes;
	private array  $classes;
	private array  $attrs;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->media             = (int) $args[ self::MEDIA ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
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
			self::LAYOUT            => 'left',
			self::MEDIA             => [],
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::CONTAINER_CLASSES => [],
			self::MEDIA_CLASSES     => [],
			self::CONTENT_CLASSES   => [],
			self::CLASSES           => [ 'c-block--full-bleed' ],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-hero__container', 'l-container' ],
			self::MEDIA_CLASSES     => [ 'b-hero__media' ],
			self::CONTENT_CLASSES   => [ 'b-hero__content' ],
			self::CLASSES           => [ 'c-block', 'b-hero' ],
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
	 * @return string
	 */
	public function get_media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return array
	 */
	public function get_content_args(): array {
		return [
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => $this->layout,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__content',
				'b-hero__content-container',
				't-theme--light'
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
				'b-hero__leadin'
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
				'b-hero__title',
				'h3'
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
				'b-hero__description',
				't-sink',
				's-sink'
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
				'b-hero__cta'
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
			Link_Controller::URL     => $cta['url'],
			Link_Controller::CONTENT => $cta['text'] ?: $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right'
			],
		];
	}

	/**
	 * @return array
	 */
	public function get_image_args(): array {
		if ( empty( $this->media ) ) {
			return [];
		}

		return [
			Image_Controller::ATTACHMENT   => Image::factory( (int) $this->media ),
			Image_Controller::AS_BG        => true,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::WRAPPER_TAG  => 'div',
			Image_Controller::CLASSES      => [ 'b-hero__figure' ],
			Image_Controller::IMG_CLASSES  => [ 'b-hero__img', 'c-image__bg' ],
			Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}
}
