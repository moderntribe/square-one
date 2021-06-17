<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\spacer;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Spacer\Spacer as Spacer_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Spacer_Block_Controller extends Abstract_Controller {

	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const SIZE              = 'size';
	public const DISPLAY_OPTIONS   = 'display_options';

	/**
	 * @var array|string
	 */
	private array $classes;
	private array $attrs;
	private string $size;
	private string $display_options;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes         = (array) $args[ self::CLASSES ];
		$this->attrs           = (array) $args[ self::ATTRS ];
		$this->size            = (string) $args[ self::SIZE ];
		$this->display_options = (string) $args[ self::DISPLAY_OPTIONS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::ATTRS           => [],
			self::CLASSES         => [],
			self::SIZE            => Spacer_Block::DEFAULT,
			self::DISPLAY_OPTIONS => Spacer_Block::ALL_SCREENS,
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES => [ 'b-spacer', 'alignfull' ],
		];
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		if ( $this->size === Spacer_Block::LARGE ) {
			$this->classes[] = 'b-spacer--large';
		}

		if ( ! empty( $this->display_options ) ) {
			$this->classes[] = $this->display_options;
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

}
