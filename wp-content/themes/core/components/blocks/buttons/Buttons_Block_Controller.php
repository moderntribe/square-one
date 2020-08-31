<?php
declare( strict_types=1 );

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
			self::CLASSES  => [ 'b-buttons' ], // Note: This block does not use `c-block` intentionally.
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
		return Markup_Utils::class_attribute( $this->attrs );
	}

	/**
	 * @return array
	 */
	public function get_buttons(): array {
		if ( empty( $this->buttons ) ) {
			return [];
		}

		return array_map( function ( $button ) {
			$link = wp_parse_args( $button[ Buttons::BUTTON_LINK ], [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] );

			if ( empty( $link['url'] ) ) {
				return [];
			}

			return [
				Link_Controller::URL        => $link['url'],
				Link_Controller::CONTENT    => $link['title'] ?? $link['url'],
				Link_Controller::TARGET     => $link['target'],
				Link_Controller::ARIA_LABEL => $button[ Buttons::BUTTON_ARIA_LABEL ] ?? '',
				Link_Controller::CLASSES    => $this->get_button_classes( $button ),
			];
		}, $this->buttons );
	}

	private function get_button_classes( $button ): array {
		$classes = [ 'b-buttons__button' ];

		if ( $button[ Buttons::BUTTON_STYLE ] === Buttons::STYLE_PRIMARY ) {
			$classes[] = 'a-btn';
		} else {
			$classes[] = sprintf( 'a-btn-%s', $button[ Buttons::BUTTON_STYLE ] );
		}

		if ( ! empty( $button[ Buttons::BUTTON_CLASSES ] ) ) {
			$classes = array_merge( $classes, explode( ' ', $button[ Buttons::BUTTON_CLASSES ] ) );
		}

		return $classes;
	}

}
