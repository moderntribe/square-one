<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\gallery_grid;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Gallery_Grid_Controller extends Abstract_Controller {

	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const GALLERY           = 'gallery';

	/**
	 * @var string[]
	 */
	private array  $classes;
	private array  $attrs;
	private array  $container_classes;
	private array  $content_classes;
	private string $title;
	private string $description;
	private array  $cta;
	private array  $gallery;	

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->gallery           = (array) $args[ self::GALLERY ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::GALLERY           => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-gallery-grid' ],
			self::CONTAINER_CLASSES => [ 'b-gallery-grid__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-gallery-grid__content' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attributes(): string {
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
	public function get_content_classes(): string {
		$this->content_classes[] = 'g-3-up';
	
		return Markup_Utils::class_attribute( $this->content_classes );
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
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-gallery-grid'
			],
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-gallery-grid__title',
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
				'b-gallery-grid__description',
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

	/**
	 * @return array
	 */
	public function get_gallery_img_ids(): array {
		$gallery_ids = [];

		if ( empty( $this->gallery ) ) {
			return $gallery_ids;
		}

		foreach ( $this->gallery as $img ) {
			if ( empty( $img ) ) {
				return [];
			}
			$gallery_ids[] = $img[ 'id' ];
		}

		return $gallery_ids;
	}

	/**
	 * @return array
	 */
	public function get_gallery_img_thumbs(): array {
		$ids          = $this->get_gallery_img_ids();
		$gallery_imgs = [];
		$i            = 1;

		if ( empty( $ids ) ) {
			return $gallery_imgs;
		}

		foreach ( $ids as $id ) {
			$gallery_imgs[] = [
				Image_Controller::IMG_ID => $id,
				Image_Controller::AS_BG => false,
				Image_Controller::USE_LAZYLOAD => false,
				Image_Controller::WRAPPER_TAG => 'div',
				Image_Controller::CLASSES => [
					'wow',
					'fadeIn',
					'zoomIn',
					'animated',
					'slow',
					'b-gallery-grid__figure',
					'b-gallery-grid__figure--' . $i,
					'delay-' . $i . 's',
				],
				Image_Controller::IMG_CLASSES => [ 'b-gallery_img' ],
				Image_Controller::SRC_SIZE => Image_Sizes::SQUARE_MEDIUM,
				Image_Controller::ATTRS => [
					'data-wow-offset' => '10',
				],
			];

			$i ++;
		}

		return $gallery_imgs;
	}
}
