<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\icon_grid;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Icon_Grid\Icon_Grid;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;

class Icon_Grid_Controller extends Abstract_Controller {

	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const ICONS             = 'icons';

	private array  $classes;
	private array  $attrs;
	private array  $container_classes;
	private array  $content_classes;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private array  $icons;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->icons             = (array) $args[ self::ICONS ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::ICONS             => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-icon-grid' ],
			self::CONTAINER_CLASSES => [ 'l-container' ],
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
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-icon-grid__header',
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
				'b-icon-grid__leadin',
				'h6',
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
				'b-icon-grid__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	public function get_cta(): Deferred_Component {
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
				'a-cta',
			],
		] );
	}

	/**
	 * @return array
	 */
	public function get_icons(): array {
		$component_args = [];
		if ( empty( $this->icons ) ) {
			return [];
		}
		foreach ( $this->icons as $icon ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $icon[ Icon_Grid::ICON_IMAGE ] ) ) {
				continue;
			}
			$image_args = [
				Image_Controller::IMG_ID       => (int) $icon[ Icon_Grid::ICON_IMAGE ],
				Image_Controller::USE_LAZYLOAD => true,
				Image_Controller::CLASSES      => [ 'b-logo__figure' ],
				Image_Controller::IMG_CLASSES  => [ 'b-logo__img' ],
				Image_Controller::SRC_SIZE     => 'large',
				Image_Controller::SRCSET_SIZES => [ 'medium', 'large' ],
			];

			$link = wp_parse_args( $icon[ Icon_Grid::ICON_LINK ], [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] );

			if ( ! empty( $icon[ Icon_Grid::ICON_LINK ] ) ) {
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
