<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\spacer;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Spacer\Spacer as Spacer_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Spacer_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const DISPLAY_OPTIONS   = 'display_options';
	public const SIZE              = 'size';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $display_options;
	private string $size;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs           = (array) $args[ self::ATTRS ];
		$this->classes         = (array) $args[ self::CLASSES ];
		$this->display_options = (string) $args[ self::DISPLAY_OPTIONS ];
		$this->size            = (string) $args[ self::SIZE ];
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_classes(): string {
		if ( $this->size === Spacer_Block::LARGE ) {
			$this->classes[] = 'b-spacer--large';
		}

		if ( ! empty( $this->display_options ) ) {
			$this->classes[] = $this->display_options;
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	protected function defaults(): array {
		return [
			self::ATTRS           => [],
			self::CLASSES         => [],
			self::DISPLAY_OPTIONS => Spacer_Block::ALL_SCREENS,
			self::SIZE            => Spacer_Block::DEFAULT,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'b-spacer', 'alignfull' ],
		];
	}

}
