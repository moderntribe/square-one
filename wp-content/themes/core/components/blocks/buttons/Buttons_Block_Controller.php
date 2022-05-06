<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\buttons;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Buttons_Block_Controller extends Abstract_Controller {

	public const ATTRS   = 'attrs';
	public const BUTTONS = 'links';
	public const CLASSES = 'classes';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $buttons;

	/**
	 * @var string[]
	 */
	private array $classes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->buttons = (array) $args[ self::BUTTONS ];
		$this->classes = (array) $args[ self::CLASSES ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_buttons(): array {
		$rows = array_filter( $this->buttons, static function ( $row ) {
			return array_key_exists( 'g-cta', $row );
		} );

		if ( 0 === count( $rows ) ) {
			return [];
		}

		return array_map( static function ( $row ) {
			return [
				Link_Controller::URL            => $row['g-cta']['link']['url'] ?? '',
				Link_Controller::CONTENT        => $row['g-cta']['link']['title'] ?? '',
				Link_Controller::TARGET         => $row['g-cta']['link']['target'] ?? '',
				Link_Controller::ADD_ARIA_LABEL => $row['g-cta']['add_aria_label'] ?? '',
				Link_Controller::ARIA_LABEL     => $row['g-cta']['aria_label'] ?? '',
				Link_Controller::CLASSES        => [ 'b-links__list-link' ],
			];
		}, $rows );
	}

	protected function defaults(): array {
		return [
			self::ATTRS   => [],
			self::BUTTONS => [],
			self::CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'b-buttons' ], // Note: This block does not use `c-block` intentionally.
		];
	}

}
