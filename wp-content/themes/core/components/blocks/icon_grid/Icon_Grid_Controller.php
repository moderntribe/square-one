<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\icon_grid;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Collections\Icon_Collection;

class Icon_Grid_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const ICONS             = 'icons';
	public const LAYOUT            = 'layout';
	public const LEADIN            = 'leadin';
	public const TITLE             = 'title';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	private Cta $cta;
	private Icon_Collection $icons;
	private string $description;
	private string $layout;
	private string $leadin;
	private string $title;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->icons             = $args[ self::ICONS ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->title             = (string) $args[ self::TITLE ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		$this->container_classes[] = 'layout-' . $this->layout;

		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		$this->content_classes[] = 'g-3-up';
		$this->content_classes[] = 'g-centered';

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_header_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LAYOUT  => Content_Block_Controller::LAYOUT_CENTER,
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

	public function get_cta(): Deferred_Component {
		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL            => $this->cta->link->url,
			Link_Controller::CONTENT        => $this->cta->link->title ?: $this->cta->link->url,
			Link_Controller::TARGET         => $this->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $this->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $this->cta->aria_label,
			Link_Controller::CLASSES        => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

	public function get_icon_card_args(): array {
		$cards = [];

		if ( ! $this->icons->count() ) {
			return $cards;
		}

		foreach ( $this->icons as $card ) {
			$cards[] = [
				Card_Controller::STYLE           => Card_Controller::STYLE_PLAIN,
				Card_Controller::TAG             => 'li',
				Card_Controller::CLASSES         => [ 'is-centered-text' ],
				Card_Controller::USE_TARGET_LINK => false,
				Card_Controller::TITLE           => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::TAG     => 'h3',
						Text_Controller::CLASSES => [ 'h5' ],
						Text_Controller::CONTENT => $card->icon_title,
					]
				),
				Card_Controller::DESCRIPTION     => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT => wpautop( $card->icon_description ),
						Container_Controller::CLASSES => [ 't-sink', 's-sink' ],
					],
				),
				Card_Controller::IMAGE           => defer_template_part(
					'components/image/image',
					null,
					[
						Image_Controller::IMG_ID       => $card->icon_image->id,
						Image_Controller::AS_BG        => false,
						Image_Controller::SRC_SIZE     => 'medium_large',
						Image_Controller::SRCSET_SIZES => [
							'medium',
							'medium_large',
						],
					],
				),
				Card_Controller::CTA             => defer_template_part(
					'components/link/link',
					null,
					[
						Link_Controller::URL            => $card->cta->link->url,
						Link_Controller::CONTENT        => $card->cta->link->title,
						Link_Controller::TARGET         => $card->cta->link->target,
						Link_Controller::ADD_ARIA_LABEL => $card->cta->add_aria_label,
						Link_Controller::ARIA_LABEL     => $card->cta->aria_label,
						Link_Controller::CLASSES        => [ 'a-cta', 'is-target-link' ],
					]
				),
			];
		}

		return $cards;
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::ICONS             => new Icon_Collection(),
			self::LAYOUT            => '',
			self::LEADIN            => '',
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [
				'c-block',
				'b-icon-grid',
			],
			self::CONTAINER_CLASSES => [ 'l-container' ],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-icon-grid__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->leadin,
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-icon-grid__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description,
		] );
	}

}
