<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\text;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Text_Controller extends Abstract_Controller {
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const CONTENT = 'content';

	private string $tag;
	private array  $classes;
	private array  $attrs;
	private string $content;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tag     = (string) $args['tag'];
		$this->classes = (array) $args['classes'];
		$this->attrs   = (array) $args['attrs'];
		$this->content = (string) $args['content'];
	}

	protected function defaults(): array {
		return [
			'tag'     => 'p',
			'classes' => [],
			'attrs'   => [],
			'content' => '',
		];
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
