<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\button;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * @var string
	 */
	private $content;
	/**
	 * @var string
	 */
	private $aria_label;
	/**
	 * @var string
	 */
	public $wrapper_tag;
	/**
	 * @var string[]
	 */
	private $wrapper_classes;
	/**
	 * @var string[]
	 */
	private $wrapper_attrs;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->classes         = (array) $args['classes'];
		$this->attrs           = (array) $args['attrs'];
		$this->type            = $args['type'];
		$this->aria_label      = $args['aria_label'];
		$this->content         = $args['content'];
		$this->wrapper_tag     = $args['wrapper_tag'];
		$this->wrapper_classes = (array) $args['wrapper_classes'];
		$this->wrapper_attrs   = (array) $args['wrapper_attrs'];
	}

	protected function defaults(): array {
		return [
			'classes'         => [],
			'attrs'           => [],
			'type'            => '',
			'aria_label'      => '',
			'content'         => '',
			'wrapper_tag'     => '',
			'wrapper_classes' => [],
			'wrapper_attrs'   => [],
		];
	}

	protected function required(): array {
		return [];
	}

	public function has_content(): bool {
		return ! empty( $this->content );
	}

	public function content(): string {
		return $this->content;
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		$attributes = $this->attrs;
		if ( $this->type ) {
			$attributes['type'] = $this->type;
		}
		if ( $this->aria_label ) {
			$attributes['aria-label'] = $this->aria_label;
		}

		return Markup_Utils::concat_attrs( $attributes );
	}

	public function wrapper_tag_open(): string {
		if ( empty( $this->wrapper_tag ) ) {
			return '';
		}
		return sprintf( '<%s%s %s>', tag_escape( $this->wrapper_tag ), Markup_Utils::class_attribute( $this->wrapper_classes ), Markup_Utils::concat_attrs( $this->wrapper_attrs ) );
	}

	public function wrapper_tag_close(): string {
		if ( empty( $this->wrapper_tag ) ) {
			return '';
		}
		return sprintf( '</%s>', tag_escape( $this->wrapper_tag ) );
	}
}
