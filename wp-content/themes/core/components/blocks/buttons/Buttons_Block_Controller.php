<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\buttons;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Buttons\Buttons;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Buttons_Block_Controller extends Abstract_Controller {

	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const BUTTONS = 'links';

	private array $classes;
	private array $attrs;
	private array $buttons;

	/**
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->buttons = (array) $args[ self::BUTTONS ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
			self::BUTTONS => [],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES => [ 'b-buttons' ], // Note: This block does not use `c-block` intentionally.
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}


	/**
	 * @return array
	 */
	public function get_buttons(): array {
		$rows = array_filter( $this->buttons, static function ( $row ) {
			return array_key_exists( 'g-cta', $row );
		} );

		if ( empty( $rows ) ) {
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

	private function get_button_classes( $button ): array {
		$classes = [ 'b-buttons__button' ];

		switch ( $button[ Buttons::BUTTON_STYLE ] ) {
			case Buttons::STYLE_SECONDARY:
				$classes[] = sprintf( 'a-btn-%s', $button[ Buttons::BUTTON_STYLE ] );
				break;
			case Buttons::STYLE_CTA:
				$classes[] = 'a-cta';
				break;
			default:
				$classes[] = 'a-btn';
				break;
		}

		if ( ! empty( $button[ Buttons::BUTTON_CLASSES ] ) ) {
			$classes = array_merge( $classes, explode( ' ', $button[ Buttons::BUTTON_CLASSES ] ) );
		}

		return $classes;
	}

}
