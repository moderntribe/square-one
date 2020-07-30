<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\button;

use Tribe\Libs\Utils\Markup_Utils;

class Controller {
	private $type;
	private $classes;
	private $attrs;
	private $content;
	private $aria_label;

	public function __construct( array $args = [] ) {
		$this->type       = $args['type'] ?? '';
		$this->classes    = (array) ( $args['classes'] ?? [] );
		$this->attrs      = (array) ( $args['attrs'] ?? [] );
		$this->content    = $args['content'] ?? '';
		$this->aria_label = $args['aria_label'] ?? '';
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

	public static function factory( array $args = [] ): self {
		return new self( $args );
	}

}
