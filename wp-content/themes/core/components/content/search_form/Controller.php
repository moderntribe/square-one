<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\search_form;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var string[]
	 */
	private $classes;

	public function __construct( $args ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->classes = (array) $args[ 'classes' ];
	}

	protected function defaults(): array {
		return [
			'classes' => [],
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-search' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function action(): string {
		return get_home_url();
	}

	public function render_button(): void {
		get_template_part( 'components/button/button', null, [
			'content' => __( 'Search', 'tribe' ),
			'classes' => [ 'c-button' ],
			'type'    => 'submit',
			'attrs'   => [
				'name'  => 'submit',
				'value' => __( 'Search', 'tribe' ),
			],
		] );
	}
}
