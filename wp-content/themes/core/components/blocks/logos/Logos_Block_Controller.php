<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\logos;

use Tribe\Project\Blocks\Types\Logos\Logos;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\Image\Image_Controller;
use Tribe\Project\Templates\Models\Image;

class Logos_Block_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTAINER_ATTRS   = 'container_attrs';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const CONTENT           = 'content';
	public const CTA               = 'cta';
	public const LOGOS             = 'logos';

	public array $classes;
	public array $attrs;
	public string $title;
	public string $content;
	public array $container_classes;
	public array $container_attrs;
	public array $content_classes;
	public array $cta;
	public array $logos;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->content           = (string) $args[ self::CONTENT ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->container_attrs   = (array) $args[ self::CONTAINER_ATTRS ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->logos             = (array) $args[ self::LOGOS ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::TITLE             => '',
			self::CONTENT           => '',
			self::CTA               => [],
			self::LOGOS             => [],
			self::CONTAINER_CLASSES => [],
			self::CONTAINER_ATTRS   => [],
			self::CONTENT_CLASSES   => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-logos' ],
			self::CONTAINER_CLASSES => [ 'l-container', 'b-logos__container' ],
			self::CONTENT_CLASSES   => [ 'b-logos__list' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return array
	 */
	public function get_header_args(): array {
		return [
			'tag'     => 'header',
			'classes' => [ 'b-logos__header' ],
			'layout'  => Content_Block_Controller::LAYOUT_LEFT,
			'title'   => $this->get_title(),
			'content' => $this->get_content(),
			'cta'     => defer_template_part( 'components/container/container', null, [
				'tag'     => 'p',
				'classes' => [ 'b-logos__cta' ],
				'content' => $this->get_cta(),
			] ),
		];
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__title', 'h3' ],
			'content' => $this->title,
		] );
	}

	public function get_content(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__description' ],
			'content' => $this->content,
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
	public function container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	/**
	 * @return string
	 */
	public function content_classes(): string {
		$this->content_classes[] = sprintf( 'b-logos--count-%d', count( $this->logos ) );

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_cta(): string {
		if ( empty( $this->cta[ 'url' ] ) ) {
			return '';
		}

		return tribe_template_part( 'components/link/link', null, [
			Link_Controller::CLASSES => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			Link_Controller::URL     => $this->cta[ 'url' ],
			Link_Controller::TARGET  => $this->cta[ 'target' ],
			Link_Controller::CONTENT => $this->cta[ 'content' ],
		] );
	}

	public function get_logos() {
		$component_args = [];
		foreach ( $this->logos as $logo ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $logo[ Logos::LOGO_IMAGE ] ) ) {
				continue;
			}
			$image_args = [
				Image_Controller::ATTACHMENT   => Image::factory( (int) $logo[ Logos::LOGO_IMAGE ] ),
				Image_Controller::USE_LAZYLOAD => true,
				Image_Controller::CLASSES      => [ 'b-logo__figure' ],
				Image_Controller::IMG_CLASSES  => [ 'b-logo__img' ],
				Image_Controller::SRC_SIZE     => 'large',
				Image_Controller::SRCSET_SIZES => [ 'medium', 'large' ],
			];

			$link = wp_parse_args( $logo[ Logos::LOGO_LINK ], [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] );

			if ( ! empty( $logo[ Logos::LOGO_LINK ] ) ) {
				$image_args[ Image_Controller::LINK_URL ]     = $link['url'];
				$image_args[ Image_Controller::LINK_TARGET ]  = $link['target'];
				$image_args[ Image_Controller::LINK_TITLE ]   = $link['title'];
				$image_args[ Image_Controller::LINK_CLASSES ] = [ 'b-logo__link' ];
			}
			$component_args[] = $image_args;
		}

		return $component_args;
	}
}
