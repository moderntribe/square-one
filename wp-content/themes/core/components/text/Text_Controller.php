<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\text;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Text_Controller extends Abstract_Controller {
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const CONTENT = 'content';

	/**
	 * @var string|Deferred_Component
	 */
	private $content;
	private string $tag;
	private array  $classes;
	private array  $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tag     = (string) $args[ self::TAG ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->content = $args[ self::CONTENT ];
	}

	protected function defaults(): array {
		return [
			self::TAG     => 'p',
			self::CLASSES => [],
			self::ATTRS   => [],
			self::CONTENT => '',
		];
	}

	protected function required(): array {
		return [];
	}

	public function get_tag(): string {
		return tag_escape( $this->tag );
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_content(): string {
		return $this->content;
	}
}
