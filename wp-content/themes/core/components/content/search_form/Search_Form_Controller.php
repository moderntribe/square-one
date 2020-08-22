<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\search_form;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\button\Button_Controller;

class Search_Form_Controller extends Abstract_Controller {
	public const CLASSES     = 'classes';
	public const FORM_ID     = 'form_id';
	public const ACTION      = 'action';
	public const PLACEHOLDER = 'placeholder';
	public const LABEL       = 'label';

	private array  $classes;
	private string $form_id;
	private string $action;
	private string $placeholder;
	private string $label;

	public function __construct( $args ) {
		$args = $this->parse_args( $args );

		$this->classes     = (array) $args[ self::CLASSES ];
		$this->form_id     = (string) $args[ self::FORM_ID ];
		$this->action      = (string) $args[ self::ACTION ];
		$this->placeholder = (string) $args[ self::PLACEHOLDER ];
		$this->label       = (string) $args[ self::LABEL ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES     => [],
			self::FORM_ID     => 's',
			self::ACTION      => get_home_url(),
			self::PLACEHOLDER => '',
			self::LABEL       => esc_html__( 'Search', 'tribe' ),
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-search' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_form_id(): string {
		return esc_attr( $this->form_id );
	}

	public function get_action(): string {
		return esc_url( $this->action );
	}

	public function get_placeholder(): string {
		return esc_attr__( $this->placeholder );
	}

	public function get_label(): string {
		return esc_html( $this->label );
	}

	public function get_button_args(): array {
		return [
			Button_Controller::CLASSES    => [ 'c-button' ],
			Button_Controller::ATTRS      => [ 'name'  => 'submit' ],
			Button_Controller::TYPE       => 'submit',
			Button_Controller::CONTENT    => esc_html__( 'Search', 'tribe' ),
		];
	}
}
