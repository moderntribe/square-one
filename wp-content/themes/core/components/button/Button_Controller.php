<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\button;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Button_Controller extends Abstract_Controller {
	public const TYPE       = 'type';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';
	public const ARIA_LABEL = 'aria_label';

	/**
	 * @var string|Deferred_Component
	 */
	private $content;
	private string $type;
	private array $classes;
	private array $attrs;
	private string $aria_label;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->type       = (string) $args[ self::TYPE ];
		$this->aria_label = (string) $args[ self::ARIA_LABEL ];
		$this->content    = $args[ self::CONTENT ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES    => [],
			self::ATTRS      => [],
			self::TYPE       => '',
			self::ARIA_LABEL => '',
			self::CONTENT    => '',
		];
	}

	public function has_content(): bool {
		return ! empty( $this->content );
	}

	public function content(): string {
		return $this->content;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		$attributes = $this->attrs;

		if ( $this->type ) {
			$attributes['type'] = $this->type;
		}

		if ( $this->aria_label ) {
			$attributes['aria-label'] = $this->aria_label;
		}

		return Markup_Utils::concat_attrs( $attributes );
	}
}
