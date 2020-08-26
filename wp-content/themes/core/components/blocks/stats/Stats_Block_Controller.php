<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\stats;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Stats\Stats;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Statistic as Statistic_Model;
use \Tribe\Project\Templates\Components\statistic\Statistic_Controller;
use \Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use \Tribe\Project\Templates\Components\text\Text_Controller;

class Stats_Block_Controller extends Abstract_Controller {
	public const LAYOUT            = 'layout';
	public const DISPLAY_DIVIDERS  = 'display_dividers';
	public const CONTENT_ALIGN     = 'content_align';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const STATS             = 'stats';

	/**
	 * @var Statistic_Model[] The collection of stats to render. Each item should be a \Tribe\Project\Templates\Models\Statistic object.
	 */
	private array  $stats;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private string $layout;
	private string $content_align;
	private string $display_dividers;
	private array  $container_classes;
	private array  $content_classes;
	private array  $classes;
	private array  $attrs;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->content_align     = (string) $args[ self::CONTENT_ALIGN ];
		$this->display_dividers  = (string) $args[ self::DISPLAY_DIVIDERS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->stats             = (array) $args[ self::STATS ];

		$this->get_stats();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::LAYOUT            => Stats::LAYOUT_STACKED,
			self::CONTENT_ALIGN     => Stats::CONTENT_ALIGN_CENTER,
			self::DISPLAY_DIVIDERS  => Stats::DISPLAY_DIVIDERS,
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::STATS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-stats' ],
			self::CONTAINER_CLASSES => [ 'b-stats__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-stats__content' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'b-stats--layout-' . $this->layout;

		if ( $this->display_dividers ) {
			$this->classes[] = 'b-stats--display_dividers';
		}
		$this->classes[] = 'b-stats--content-align-' . $this->content_align;

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
		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => Stats::CONTENT_ALIGN_CENTER === $this->content_align ? Content_Block_Controller::LAYOUT_CENTER : Content_Block_Controller::LAYOUT_STACKED,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-stats__header'
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
				'b-stats__leadin'
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
				'b-stats__title',
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
				'b-stats__description',
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
				'b-stats__cta'
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
	 * Loop through the stats provided setup the statistic components arguments for each.
	 *
	 * @return array
	 */
	public function get_stats(): array {
		$statistic_args = [];

		foreach ( $this->stats as $item ) {
			$statistic_args[] = [
				Statistic_Controller::VALUE => defer_template_part( 'components/text/text', null, $this->get_value_args( $item ) ),
				Statistic_Controller::LABEL => defer_template_part( 'components/text/text', null, $this->get_label_args( $item ) ),
			];
		}

		return $statistic_args;
	}

	/**
	 * @param Statistic_Model $item
	 *
	 * @return array
	 */
	private function get_value_args( Statistic_Model $item ): array {
		return [
			Text_Controller::CONTENT => esc_html( $item->value ),
		];
	}

	/**
	 * @param Statistic_Model $item
	 *
	 * @return array
	 */
	private function get_label_args( Statistic_Model $item ): array {
		return [
			Text_Controller::CONTENT => esc_html( $item->label ),
		];
	}
}
