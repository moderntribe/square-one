<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\footer\single_footer;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Single_Footer_Controller extends Abstract_Controller {

	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	public const TAGS_LIST_COMPONENT = 'tags_component';

	private array $classes;
	private array $attrs;
	public Deferred_Component $tags_list_component;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes             = (array) $args[ self::CLASSES ];
		$this->attrs               = (array) $args[ self::ATTRS ];
		$this->tags_list_component = $args[ self::TAGS_LIST_COMPONENT ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES => ['c-single-footer'],
			self::ATTRS   => [],
		];
	}

	protected function required(): array {
		return [];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

}
