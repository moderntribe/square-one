<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\section_nav;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\button\Button_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Section_Nav_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_ATTRS   = 'container_attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MENU_ID           = 'menu';
	public const TOGGLE_LABEL      = 'toggle_label';

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
	 * @var int
	 */
	private int $menu_id;

	/**
	 * @var string
	 */
	private string $toggle_label;

	/**
	 * @var string
	 */
	private string $unique_id;

	/**
	 * @var string
	 */
	private string $container_id;

	public function __construct( array $args = [] ) {
		$this->unique_id    = uniqid();
		$this->container_id = sprintf( 'c-section__container--%s', $this->unique_id );

		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_attrs   = (array) $args[ self::CONTAINER_ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->menu_id           = (int) $args[ self::MENU_ID ];
		$this->toggle_label      = (string) $args[ self::TOGGLE_LABEL ];
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_container_attrs(): string {
		return Markup_Utils::concat_attrs( $this->container_attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_mobile_toggle(): Deferred_Component {
		return defer_template_part( 'components/button/button', null, [
			Button_Controller::ATTRS   => [
				'data-js'       => 'c-section-nav__container-toggle',
				'aria-controls' => $this->container_id,
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
			],
			Button_Controller::CLASSES => [ 'c-section-nav__container-toggle' ],
			Button_Controller::CONTENT => esc_html( $this->toggle_label ),
		] );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_ATTRS   => [],
			self::CONTAINER_CLASSES => [],
			self::MENU_ID           => 0,
			self::TOGGLE_LABEL      => esc_html__( 'In this section', 'tribe' ),
		];
	}

	protected function required(): array {
		return [
			self::ATTRS             => [ 'data-js' => 'c-section-nav' ],
			self::CLASSES           => [ 'c-section-nav' ],
			self::CONTAINER_ATTRS   => [
				'id'      => $this->container_id,
				'data-js' => 'c-section-nav__container',
			],
			self::CONTAINER_CLASSES => [ 'c-section-nav__container' ],
		];
	}

}
