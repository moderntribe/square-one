<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\container;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Controller extends Abstract_Controller {
	/**
	 * @var string
	 */
	private $tag;
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * @var string|Deferred_Component
	 */
	private $content;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->tag     = $args['tag'];
		$this->classes = (array) $args['classes'];
		$this->attrs   = (array) $args['attrs'];
		$this->content = $args['content'];
	}

	protected function defaults(): array {
		return [
			'tag'     => 'div',
			'classes' => [],
			'attrs'   => [],
			'content' => '',
		];
	}

	protected function required(): array {
		return [];
	}

	public function tag(): string {
		return tag_escape( $this->tag );
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function content(): string {
		return (string) $this->content;
	}
}
