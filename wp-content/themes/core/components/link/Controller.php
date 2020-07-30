<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\link;

use Tribe\Libs\Utils\Markup_Utils;

class Controller {

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
	 * @var array
	 */
	private $classes;
	/**
	 * @var array
	 */
	private $attrs;
	/**
	 * @var string
	 */
	private $content;

	public function __construct( array $args = [] ) {
		$this->url        = $args['url'] ?? '';
		$this->target     = $args['target'] ?? '';
		$this->aria_label = $args['aria_label'] ?? '';
		$this->classes    = (array) ( $args['classes'] ?? [] );
		$this->attrs      = (array) ( $args['attrs'] ?? [] );
		$this->content    = $args['content'] ?? '';
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attrs(): string {
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
			__( '(Opens new window', 'tribe' )
		);
	}

	public static function factory( array $args = [] ): self {
		return new self( $args );
	}
}
