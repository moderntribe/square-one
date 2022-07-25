<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\stats;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Stats\Stats;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\statistic\Statistic_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Models\Collections\Statistic_Collection;
use Tribe\Project\Templates\Models\Statistic;

class Stats_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_ALIGN     = 'content_align';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const DIVIDERS          = 'dividers';
	public const LAYOUT            = 'layout';
	public const LEADIN            = 'leadin';
	public const STATS             = 'stats';
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

	/**
	 * The collection of stats to render.
	 */
	private Statistic_Collection $stats;
	private string $content_align;
	private string $description;
	private string $dividers;
	private string $layout;
	private string $leadin;
	private string $title;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_align     = (string) $args[ self::CONTENT_ALIGN ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->dividers          = (string) $args[ self::DIVIDERS ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->stats             = $args[ self::STATS ];
		$this->title             = (string) $args[ self::TITLE ];

		$this->get_stats();
	}

	public function get_classes(): string {
		$this->classes[] = 'b-stats--layout-' . $this->layout;
		$this->classes[] = 'b-stats--content-align-' . $this->content_align;
		$this->classes[] = 'b-stats--dividers-' . $this->dividers;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		$this->attrs[] = [
			'data-stats-count' => count( $this->stats ),
		];

		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

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
				'b-stats__header',
			],
		];
	}

	/**
	 * Loop through the stats provided & set up the statistic
	 * components arguments for each.
	 *
	 * @return array<string, \Tribe\Project\Templates\Components\Deferred_Component[]>
	 */
	public function get_stats(): array {
		return array_map( static fn( Statistic $stat ) => [
			Statistic_Controller::VALUE => defer_template_part(
				'components/text/text',
				'',
				[
					Text_Controller::CONTENT => esc_html( $stat->row_value ),
				]
			),
			Statistic_Controller::LABEL => defer_template_part(
				'components/text/text',
				'',
				[
					Text_Controller::CONTENT => esc_html( $stat->row_label ),
				]
			),
		], $this->stats->items() );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_ALIGN     => Stats::CONTENT_ALIGN_CENTER,
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::DIVIDERS          => Stats::DIVIDERS_SHOW,
			self::LAYOUT            => Stats::LAYOUT_STACKED,
			self::LEADIN            => '',
			self::STATS             => new Statistic_Collection(),
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-stats' ],
			self::CONTAINER_CLASSES => [ 'b-stats__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-stats__content' ],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-stats__leadin',
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
				'b-stats__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-stats__description',
			],
			Container_Controller::CONTENT => $this->description,
		] );
	}

	private function get_cta(): Deferred_Component {
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

}
