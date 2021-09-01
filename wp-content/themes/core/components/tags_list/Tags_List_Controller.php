<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\tags_list;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Tags_List_Controller extends Abstract_Controller {

	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const TAGS    = 'tags';

	private array $classes;
	private array $attrs;

	/**
	 * @var array List of tags and links. [tag => link], example: ['news'=> 'https://abc.xyz/news',...]
	 */
	private array $tags = [];

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];

		$this->tags = (array) $args[ self::TAGS ];
	}

	protected function defaults(): array {
		return [
			self::TAGS    => [],
			self::CLASSES => ['c-tags-list'],
			self::ATTRS   => [],
		];
	}

	protected function required(): array {
		return [];
	}

	public function get_tags(): array {
		return $this->tags;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes, false );
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

}
