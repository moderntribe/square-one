<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\text;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	public $content;
	public $tag;
	private $classes;
	private $attrs;

	public function __construct( array $args = [] ) {
		$this->tag     = $args['tag'] ?? 'p';
		$this->classes = (array) ( $args['classes'] ?? [] );
		$this->attrs   = (array) ( $args['attrs'] ?? [] );
		$this->content = $args['content'] ?? '';
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}
}
