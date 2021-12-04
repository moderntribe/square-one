<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\section_nav;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\button\Button_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\navigation\Navigation_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Section_Nav_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_ATTRS   = 'container_attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MENU              = 'menu';
	public const MOBILE_LABEL      = 'mobile_label';
	public const DESKTOP_LABEL     = 'desktop_label';
	public const MORE_LABEL        = 'more_label';
	public const STICKY            = 'sticky';
	public const MOBILE_INIT_OPEN  = 'mobile_init_open';

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
	private array $container_attrs;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var int|string|\WP_Term
	 */
	private $menu;

	/**
	 * @var string
	 */
	private string $mobile_label;

	/**
	 * @var string
	 */
	private string $desktop_label;

	/**
	 * @var string
	 */
	private string $more_label;

	/**
	 * @var bool
	 */
	private bool $sticky;

	/**
	 * @var bool
	 */
	private bool $mobile_init_open;

	/**
	 * @var string
	 */
	private string $unique_id;

	/**
	 * @var string
	 */
	private string $container_id;

	/**
	 * @var string
	 */
	private string $more_id;

	public function __construct( array $args = [] ) {
		$this->unique_id    = uniqid();
		$this->container_id = sprintf( 'c-section-nav__container--%s', $this->unique_id );
		$this->more_id      = sprintf( 'c-section-nav__more--%s', $this->unique_id );

		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_attrs   = (array) $args[ self::CONTAINER_ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->menu              = $args[ self::MENU ];
		$this->mobile_label      = (string) $args[ self::MOBILE_LABEL ];
		$this->desktop_label     = (string) $args[ self::DESKTOP_LABEL ];
		$this->more_label        = (string) $args[ self::MORE_LABEL ];
		$this->sticky            = (bool) $args[ self::STICKY ];
		$this->mobile_init_open  = (bool) $args[ self::MOBILE_INIT_OPEN ];
	}

	public function get_attrs(): string {
		if ( $this->mobile_init_open ) {
			$this->attrs['data-init-open'] = 'true';
		}

		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_classes(): string {
		if ( $this->sticky ) {
			$this->classes[] = 'c-section-nav--sticky';
		}

		if ( $this->mobile_init_open ) {
			$this->classes[] = 'c-section-nav--visible';
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_more_attrs(): string {
		return Markup_Utils::concat_attrs( [
			'id'      => $this->more_id,
			'data-js' => 'c-section-nav__more',
		] );
	}

	public function get_more_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-section-nav__more' ] );
	}

	public function get_more_toggle(): Deferred_Component {
		return defer_template_part( 'components/button/button', null, [
			Button_Controller::ATTRS   => [
				'data-js'       => 'c-section-nav__toggle--more',
				'aria-controls' => $this->more_id,
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
			],
			Button_Controller::CLASSES => [ 'c-section-nav__toggle', 'c-section-nav__toggle--more' ],
			Button_Controller::CONTENT => esc_html( $this->more_label ),
		] );
	}

	public function get_mobile_toggle(): Deferred_Component {
		return defer_template_part( 'components/button/button', null, [
			Button_Controller::ATTRS   => [
				'data-js'       => 'c-section-nav__toggle--mobile',
				'aria-controls' => $this->container_id,
				'aria-expanded' => $this->mobile_init_open ? 'true' : 'false',
				'aria-haspopup' => 'true',
			],
			Button_Controller::CLASSES => [ 'c-section-nav__toggle', 'c-section-nav__toggle--mobile' ],
			Button_Controller::CONTENT => esc_html( $this->mobile_label ),
		] );
	}

	public function get_desktop_label(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [ 'c-section-nav__label--desktop' ],
			Text_Controller::CONTENT => $this->desktop_label,
		] );
	}

	public function get_nav_menu(): Deferred_Component {
		return defer_template_part( 'components/navigation/navigation', null, [
			Navigation_Controller::MENU             => $this->menu,
			Navigation_Controller::MENU_LOCATION    => 'section-nav',
			Navigation_Controller::NAV_LIST_CLASSES => [ 'c-section-nav__list' ],
			Navigation_Controller::NAV_LIST_ATTRS   => [ 'data-js' => 'c-section-nav__list' ],
		] );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_ATTRS   => [],
			self::CONTAINER_CLASSES => [],
			self::MENU              => 0,
			self::MOBILE_LABEL      => esc_html__( 'In this section', 'tribe' ),
			self::DESKTOP_LABEL     => '',
			self::MORE_LABEL        => esc_html__( 'More', 'tribe' ),
			self::STICKY            => false,
			self::MOBILE_INIT_OPEN  => false,
		];
	}

	protected function required(): array {
		return [
			self::ATTRS             => [
				'id'      => sprintf( 'c-section-nav--%s', $this->unique_id ),
				'data-js' => 'c-section-nav',
			],
			self::CLASSES           => [ 'c-section-nav' ],
			self::CONTAINER_ATTRS   => [
				'id'      => $this->container_id,
				'data-js' => 'c-section-nav__container',
			],
			self::CONTAINER_CLASSES => [ 'c-section-nav__container' ],
		];
	}

}
