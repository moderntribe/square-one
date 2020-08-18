<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\sidebar;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var string
	 */
	private $sidebar_id;
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->sidebar_id = $args['sidebar_id'];
		$this->classes    = (array) $args[ 'classes' ];
		$this->attrs      = (array) $args[ 'attrs' ];
	}

	protected function defaults(): array {
		return [
			'sidebar_id' => '',
			'classes'    => [],
			'attrs'      => [ 'role' => 'complementary' ],
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'sidebar' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function active(): bool {
		return $this->sidebar_id && is_active_sidebar( $this->sidebar_id );
	}

	public function id(): string {
		return $this->sidebar_id;
	}

}
