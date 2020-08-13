<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\trackback;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

/**
 * Class Trackback
 */
class Controller extends Abstract_Controller {

	/**
	 * @var int
	 */
	public $comment_id;

	/**
	 * @var array
	 */
	public $classes;

	/**
	 * @var array
	 */
	public $attrs;

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var string
	 */
	public $edit_link;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}
		$this->comment_id  = $args[ 'comment_id' ];
		$this->classes     = $args[ 'classes' ];
		$this->attrs       = $args[ 'attrs' ];
		$this->label       = $args[ 'attrs' ];
		$this->edit_link   = $args[ 'edit_link' ];
		$this->init();
	}

	protected function defaults(): array {
		return [
			'comment_id'  => 0,
			'classes'     => [],
			'attrs'       => [],
			'labels'      => '',
			'edit_link'   => '',
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [];
	}

	public function init() {
		$this->attrs[ 'id' ] = sprintf( 'comment-%d', $this->comment_id );
	}

	/**
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

}
