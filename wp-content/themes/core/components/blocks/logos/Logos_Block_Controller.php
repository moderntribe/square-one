<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\logos;

use Tribe\Project\Blocks\Types\Logos\Logos;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\Image\Image_Controller;
use Tribe\Project\Templates\Models\Image;

class Logos_Block_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const CONTENT           = 'content';
	public const CTA               = 'cta';
	public const LOGOS             = 'logos';

	public array  $classes;
	public array  $attrs;
	public string $title;
	public string $content;
	public array  $container_classes;
	public array  $content_classes;
	public array  $cta;
	public array  $logos;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->content           = (string) $args[ self::CONTENT ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
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

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		$this->content_classes[] = sprintf( 'b-logos--count-%d', count( $this->logos ) );

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return array
	 */
	public function get_header_args(): array {
		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::CLASSES => [ 'b-logos__header' ],
			Content_Block_Controller::LAYOUT  => Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [ 'b-logos__title', 'h3' ],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [ 'b-logos__description' ],
			Text_Controller::CONTENT => $this->content ?? '',
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
			Container_Controller::CLASSES => [ 'b-logos__cta' ],
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
			Link_Controller::CLASSES => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
		];
	}

	/**
	 * @return array
	 */
	public function get_logos(): array {
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
