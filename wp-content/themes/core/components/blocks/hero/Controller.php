<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\hero;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Hero\Hero as Hero_Block;
use Tribe\Project\Blocks\Types\Hero\Hero;
use Tribe\Project\Blocks\Types\Hero\Model;
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
	public $content;

	/**
	 * @var string[]
	 */
	public $container_classes;

	/**
	 * @var string[]
	 */
	public $media_classes;

	/**
	 * @var string[]
	 */
	public $content_classes;

	/**
	 * @var string []
	 */
	public $classes;

	/**
	 * @var string []
	 */
	public $attrs;

	/**
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$this->layout            = $args[ 'layout' ] ?? Hero_Block::LAYOUT_LEFT;
		$this->media             = $this->get_media( $args );
		$this->content           = $this->get_content( $args );
		$this->container_classes = (array) ( $args[ 'container_classes' ] ?? [ 'b-hero__container', 'l-container' ] );
		$this->media_classes     = (array) ( $args[ 'media_classes' ] ?? [ 'b-hero__media' ] );
		$this->content_classes   = (array) ( $args[ 'content_classes' ] ?? [ 'b-hero__content' ] );
		$this->classes           = (array) ( $args[ 'classes' ] ?? [
				'c-block',
				'b-hero',
				'c-block--full-bleed',
				'c-block--' . $this->layout,
			] );
		$this->attrs             = (array) ( $args[ 'attrs' ] ?? [] );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	protected function get_content( $args ): array {
		if ( ! isset( $args[ 'content' ] ) || empty( $args[ 'content' ] ) ) {
			return [];
		}

		return [
			'classes' => [ 'b-hero__content-container', 't-theme--light' ],
			'leadin'  => $args[ 'content' ][ Model::LEAD_IN ] ?? '',
			'title'   => $args[ 'content' ][ Model::TITLE ] ?? '',
			'text'    => $args[ 'content' ][ Model::TEXT ] ?? '',
			'action'  => $args[ 'content' ][ Model::CTA ] ?? [],
			'layout'  => $this->layout,
		];
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	protected function get_media( $args ): array {
		if ( ! isset( $args[ 'media' ] ) ) {
			return [];
		}

		return $this->get_image( $args[ 'media' ] );
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
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}
}
