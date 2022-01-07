<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\statistic;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Statistic_Controller extends Abstract_Controller {

	public const ATTRS   = 'attrs';
	public const CLASSES = 'classes';
	public const LABEL   = 'label';
	public const TAG     = 'tag';
	public const VALUE   = 'value';

	private ?Deferred_Component $label;
	private ?Deferred_Component $value;

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $tag;

	public function __construct( array $args ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->label   = $args[ self::LABEL ];
		$this->tag     = (string) $args[ self::TAG ];
		$this->value   = $args[ self::VALUE ];
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

	public function get_value(): ?Deferred_Component {
		if ( empty( $this->value ) ) {
			return null;
		}

		if ( empty( $this->value['tag'] ) ) {
			$this->value['tag'] = 'div';
		}

		$this->value['classes'][] = 'c-statistic__value';

		return $this->value;
	}

	public function get_label(): ?Deferred_Component {
		if ( empty( $this->label ) ) {
			return null;
		}

		if ( empty( $this->label['tag'] ) ) {
			$this->label['tag'] = 'div';
		}

		$this->label['classes'][] = 'c-statistic__label';

		return $this->label;
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::CLASSES => [],
			self::LABEL   => null,
			self::TAG     => 'div',
			self::VALUE   => null,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-statistic' ],
		];
	}

}
