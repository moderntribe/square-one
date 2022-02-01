<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\button;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Button_Controller extends Abstract_Controller {

	public const ARIA_LABEL = 'aria_label';
	public const ATTRS      = 'attrs';
	public const CLASSES    = 'classes';
	public const CONTENT    = 'content';
	public const TYPE       = 'type';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $aria_label;

	/**
	 * @var string|\Tribe\Project\Templates\Components\Deferred_Component
	 */
	private $content;
	private string $type;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->aria_label = (string) $args[ self::ARIA_LABEL ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->content    = $args[ self::CONTENT ];
		$this->type       = (string) $args[ self::TYPE ];
	}

	public function has_content(): bool {
		return ! empty( $this->content );
	}

	public function get_content(): string {
		return (string) $this->content;
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

	protected function defaults(): array {
		return [
			self::ARIA_LABEL => '',
			self::ATTRS      => [],
			self::CLASSES    => [],
			self::CONTENT    => '',
			self::TYPE       => '',
		];
	}

	protected function required(): array {
		return [];
	}

}
