<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\lead_form;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Lead_Form\Lead_Form as Lead_Form_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Lead_Form_Block_Controller extends Abstract_Controller {
	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const TITLE             = 'title';
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const FORM              = 'form';
	public const CONTAINER_CLASSES = 'container_classes';
	public const FORM_CLASSES      = 'form_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private string $width;
	private string $layout;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;
	private int    $form;
	private array  $container_classes;
	private array  $form_classes;
	private array  $classes;
	private array  $attrs;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->layout            = $args[ self::LAYOUT ];
		$this->width             = $args[ self::WIDTH ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->form              = $args[ self::FORM ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->form_classes      = (array) $args[ self::FORM_CLASSES ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::WIDTH             => Lead_Form_Block::WIDTH_GRID,
			self::LAYOUT            => Lead_Form_Block::LAYOUT_CENTER,
			self::TITLE             => '',
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::FORM              => null,
			self::CONTAINER_CLASSES => [],
			self::FORM_CLASSES      => [],
			self::CLASSES           => [],
			self::ATTRS             => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CONTAINER_CLASSES => [ 'b-lead-form__container' ],
			self::FORM_CLASSES      => [ 'b-lead-form__form' ],
			self::CLASSES           => [ 'c-block', 'b-lead-form' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'c-block--' . $this->layout;
		$this->classes[] = 'c-block--' . $this->width;
		if ( $this->width === Lead_Form_Block::WIDTH_GRID ) {
			$this->classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::class_attribute( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		if ( $this->width === Lead_Form_Block::WIDTH_FULL ) {
			$this->container_classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_form_classes(): string {
		return Markup_Utils::class_attribute( $this->form_classes );
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
			Content_Block_Controller::LAYOUT  => $this->layout === Lead_Form_Block::LAYOUT_CENTER ? Content_Block_Controller::LAYOUT_CENTER : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-lead-form__content'
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
				'b-lead-form__leadin'
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
				'b-lead-form__title',
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
				'b-lead-form__description',
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
				'b-lead-form__cta'
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
	 * @return mixed
	 */
	public function get_form_id() {
		return $this->form;
	}

	/**
	 * @return string
	 */
	public function get_form(): string {
		if ( ! function_exists( 'gravity_form' ) ) {
			return '';
		}

		return gravity_form( $this->form, false, false, false, null, false, 1, false );
	}
}
