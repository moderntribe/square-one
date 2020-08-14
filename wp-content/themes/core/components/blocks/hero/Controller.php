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
class Controller extends Abstract_Controller {

	/**
	 * @var string
	 */
	public $layout;

	/**
	 * @var string
	 */
	public $media;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $leadin;

	/**
	 * @var array
	 */
	public $cta;

	/**
	 * @var array
	 */
	public $container_classes;

	/**
	 * @var array
	 */
	public $media_classes;

	/**
	 * @var array
	 */
	public $content_classes;

	/**
	 * @var array
	 */
	public $classes;

	/**
	 * @var array
	 */
	public $attrs;

	/**
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		$this->layout            = $args[ 'layout' ];
		$this->media             = $this->get_image( $args[ 'media' ] );
		$this->title             = $args[ 'title' ];
		$this->description       = $args[ 'description' ];
		$this->cta               = (array) $args[ 'cta' ];
		$this->leadin            = $args[ 'leadin' ];
		$this->container_classes = (array) $args[ 'container_classes' ];
		$this->media_classes     = (array) $args[ 'media_classes' ];
		$this->content_classes   = (array) $args[ 'content_classes' ];
		$this->classes           = (array) $args[ 'classes' ];
		$this->attrs             = (array) $args[ 'attrs' ];
		$this->init();
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
	 * @return array
	 */
	protected function required(): array {
		return [];
	}

	/**
	 * Any required setup stuff
	 */
	protected function init() {
		$this->classes[] = 'c-block--' . $this->layout;
	}

	/**
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_content(): string {
		return tribe_template_part( 'components/content_block/content_block', null, [
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
		] );
	}

	/**
	 * @param $attachment_id
	 *
	 * @return string
	 */
	protected function get_image( $attachment_id ): string {
		if ( empty( $attachment_id ) ) {
			return '';
		}

		return tribe_template_part( 'components/image/image', null, [
			'attachment'    => Image::factory( (int) $attachment_id ),
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
		] );
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
}
