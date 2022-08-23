<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\search_form;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\button\Button_Controller;

class Search_Form_Controller extends Abstract_Controller {

	public const ACTION      = 'action';
	public const CLASSES     = 'classes';
	public const FORM_ID     = 'form_id';
	public const LABEL       = 'label';
	public const PLACEHOLDER = 'placeholder';
	public const VALUE       = 'value';

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $action;
	private string $form_id;
	private string $label;
	private string $placeholder;
	private string $value;

	public function __construct( array $args ) {
		$args = $this->parse_args( $args );

		$this->action      = (string) $args[ self::ACTION ];
		$this->classes     = (array) $args[ self::CLASSES ];
		$this->form_id     = (string) $args[ self::FORM_ID ];
		$this->label       = (string) $args[ self::LABEL ];
		$this->placeholder = (string) $args[ self::PLACEHOLDER ];
		$this->value       = (string) $args[ self::VALUE ];
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

	public function get_search_value(): string {
		return $this->value;
	}

	public function get_label(): string {
		return esc_html( $this->label );
	}

	public function get_submit_button_args(): array {
		return [
			Button_Controller::CLASSES => [ 'c-search__submit-button' ],
			Button_Controller::ATTRS   => [ 'name' => 'submit' ],
			Button_Controller::TYPE    => 'submit',
			Button_Controller::CONTENT => '<span>' . esc_html__( 'Search', 'tribe' ) . '</span>',
		];
	}

	protected function defaults(): array {
		return [
			self::CLASSES     => [],
			self::FORM_ID     => 's',
			self::ACTION      => get_home_url(),
			self::PLACEHOLDER => '',
			self::LABEL       => esc_html__( 'Search', 'tribe' ),
			self::VALUE       => '',
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-search' ],
		];
	}

}
