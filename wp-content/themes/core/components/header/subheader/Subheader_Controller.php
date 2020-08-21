<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Subheader_Controller extends Abstract_Controller {
	use Page_Title;

	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	private array $classes;
	private array $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES => [ 'c-subheader' ],
			self::ATTRS   => [],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_title_args(): array {
		if ( empty( $this->get_page_title() ) ) {
			return [];
		}

		return [
			Text_Controller::TAG     => 'h1',
			Text_Controller::CLASSES => [ 'page-title', 'h1' ],
			Text_Controller::CONTENT => $this->get_page_title(),
		];
	}
}
