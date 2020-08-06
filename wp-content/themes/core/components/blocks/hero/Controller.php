<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\hero;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Hero\Hero as Hero_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Hero
 *
 * @property string   $layout
 * @property string   $media
 * @property string   $content
 * @property string[] $container_classes
 * @property string[] $media_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
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
		$this->media             = $args[ 'media' ] ?? '';
		$this->content           = $args[ 'content' ] ?? '';
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

	public function container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	public function content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}
}
