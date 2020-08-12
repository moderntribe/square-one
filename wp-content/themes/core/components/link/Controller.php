<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\link;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var string
	 */
	private $url;
	/**
	 * @var string
	 */
	private $target;
	/**
	 * @var string
	 */
	private $aria_label;
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

		$this->url             = $args['url'];
		$this->target          = $args['target'];
		$this->aria_label      = $args['aria_label'];
		$this->classes         = (array) $args['classes'];
		$this->attrs           = (array) $args['attrs'];
		$this->content         = $args['content'];
		$this->wrapper_tag     = $args['wrapper_tag'];
		$this->wrapper_classes = (array) $args['wrapper_classes'];
		$this->wrapper_attrs   = (array) $args['wrapper_attrs'];
	}

	protected function defaults(): array {
		return [
			'url'             => '',
			'target'          => '',
			'aria_label'      => '',
			'classes'         => [],
			'attrs'           => [],
			'content'         => '',
			'wrapper_tag'     => '',
			'wrapper_classes' => [],
			'wrapper_attrs'   => [],
		];
	}

	protected function required(): array {
		return [];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		$attrs = $this->attrs;
		if ( ! empty( $this->url ) ) {
			$attrs['href'] = esc_url_raw( $this->url );
		}
		if ( ! empty( $this->aria_label ) ) {
			$attrs['aria-label'] = $this->aria_label;
		}
		if ( ! empty( $this->target ) ) {
			$attrs['target'] = $this->target;
		}
		if ( $this->target === '_blank' ) {
			$this->attrs['rel'] = 'noopener';
		}

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function content(): string {
		$content = $this->content;
		if ( $this->target === '_blank' ) {
			$content .= $this->new_window_text();
		}
		return $content;
	}

	private function new_window_text(): string {
		return sprintf(
			'<span class="u-visually-hidden">%s</span>',
			__( 'Opens new window', 'tribe' )
		);
	}

	public function wrapper_tag_open(): string {
		if ( empty( $this->wrapper_tag ) ) {
			return '';
		}
		return sprintf( '<%s%s %s>', $this->wrapper_tag, $this->wrapper_classes(), $this->wrapper_attributes() );
	}

	public function wrapper_tag_close(): string {
		if ( empty( $this->wrapper_tag ) ) {
			return '';
		}
		return sprintf( '</%s>', $this->wrapper_tag );
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function wrapper_attributes(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
	}
}
