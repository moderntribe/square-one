<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\buttons;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Collections\Button_Collection;

class Buttons_Block_Controller extends Abstract_Controller {

	public const ATTRS   = 'attrs';
	public const BUTTONS = 'links';
	public const CLASSES = 'classes';

	/**
	 * @var string[]
	 */
	private array $attrs;

	private Button_Collection $buttons;

	/**
	 * @var string[]
	 */
	private array $classes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->buttons = $args[ self::BUTTONS ];
		$this->classes = (array) $args[ self::CLASSES ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_buttons(): Button_Collection {
		return $this->buttons;
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::BUTTONS => new Button_Collection(),
			self::CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'b-buttons' ], // Note: This block does not use `c-block` intentionally.
		];
	}

}
