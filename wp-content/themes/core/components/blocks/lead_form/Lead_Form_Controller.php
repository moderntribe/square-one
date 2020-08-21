<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\lead_form;

use Tribe\Libs\Utils\Markup_Utils;
use \Tribe\Project\Blocks\Types\Lead_Form\Lead_Form as Lead_Form_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

/**
 * Class Lead_Form
 */
class Lead_Form_Controller extends Abstract_Controller {

	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const FORM              = 'form';
	public const CONTAINER_CLASSES = 'container_classes';
	public const FORM_CLASSES      = 'form_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private string $width;
	private string $layout;
	private string $title;
	private string $description;
	private int $form;
	private array $container_classes;
	private array $form_classes;
	private array $classes;
	private array $attrs;

	public function __construct( array $args = [] ) {
		$args                    = $this->parse_args( $args );
		$this->layout            = $args[ self::LAYOUT ];
		$this->width             = $args[ self::WIDTH ];
		$this->title             = $args[ self::TITLE ];
		$this->description       = $args[ self::DESCRIPTION ];
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
			self::DESCRIPTION       => '',
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
	public function get_container_classes(): string {
		if ( $this->width === Lead_Form_Block::WIDTH_FULL ) {
			$this->container_classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = 'b-lead-form--layout-' . $this->layout;
		$this->classes[] = 'b-lead-form--width-' . $this->width;

		if ( $this->width === Lead_Form_Block::WIDTH_GRID ) {
			$this->classes[] = 'l-container';
		}

		if ( $this->width === Lead_Form_Block::WIDTH_FULL ) {
			$this->classes[] = 'c-block--full-bleed';
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
	 * @return array
	 */
	public function get_content_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::CLASSES => [ 'b-lead-form__content' ],
			Content_Block_Controller::LAYOUT  => $this->layout === Lead_Form_Block::LAYOUT_CENTER ? Content_Block_Controller::LAYOUT_CENTER : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::TITLE   => defer_template_part( 'components/text/text', null, $this->get_title_args() ),
			Content_Block_Controller::CONTENT => defer_template_part( 'components/text/text', null, $this->get_description_args() ),
		];
	}

	/**
	 * @return array
	 */
	protected function get_title_args(): array {
		return [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [ 'b-lead-form__title', 'h3' ],
			Text_Controller::CONTENT => esc_html( $this->title ),
		];
	}

	/**
	 * @return array
	 */
	protected function get_description_args(): array {
		return [
			Text_Controller::CLASSES => [ 'b-lead-form__description', 't-sink', 's-sink' ],
			Text_Controller::CONTENT => esc_html( $this->description ),
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

	/**
	 * @return string
	 */
	public function get_form_classes(): string {
		return Markup_Utils::class_attribute( $this->form_classes );
	}
}
