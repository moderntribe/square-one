<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\link;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Link_Controller extends Abstract_Controller {

	public const URL            = 'url';
	public const TARGET         = 'target';
	public const ADD_ARIA_LABEL = 'add_aria_label';
	public const ARIA_LABEL     = 'aria_label';
	public const CLASSES        = 'classes';
	public const ATTRS          = 'attrs';
	public const CONTENT        = 'content';
	public const HEADER         = 'header';
	public const DESCRIPTION    = 'description';

	/**
	 * @var string|\Tribe\Project\Templates\Components\Deferred_Component
	 */
	private $content;
	private string $url;
	private string $target;
	private bool $add_aria_label;
	private string $aria_label;
	private array $classes;
	private array $attrs;
	private string $link_header;
	private string $description;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->url            = (string) $args[ self::URL ];
		$this->target         = (string) $args[ self::TARGET ];
		$this->add_aria_label = (bool) $args[ self::ADD_ARIA_LABEL ];
		$this->aria_label     = (string) $args[ self::ARIA_LABEL ];
		$this->classes        = (array) $args[ self::CLASSES ];
		$this->attrs          = (array) $args[ self::ATTRS ];
		$this->content        = (string) $args[ self::CONTENT ];
		$this->link_header    = (string) $args[ self::HEADER ];
		$this->description    = (string) $args[ self::DESCRIPTION ];
	}

	protected function defaults(): array {
		return [
			self::URL            => '',
			self::TARGET         => '',
			self::ADD_ARIA_LABEL => false,
			self::ARIA_LABEL     => '',
			self::CLASSES        => [],
			self::ATTRS          => [],
			self::CONTENT        => '',
			self::HEADER         => '',
			self::DESCRIPTION    => '',
		];
	}

	protected function required(): array {
		return [];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		$attrs = $this->attrs;

		if ( ! empty( $this->url ) ) {
			$attrs['href'] = esc_url_raw( $this->url );
		}

		if ( $this->add_aria_label && ! empty( $this->aria_label ) ) {
			$attrs['aria-label'] = $this->aria_label;
		}

		if ( ! empty( $this->target ) ) {
			$attrs['target'] = $this->target;
		}

		if ( $this->target === '_blank' ) {
			$attrs['rel'] = 'noopener';
		}

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function get_content(): string {
		$content = $this->content;

		if ( ! is_string( $content ) ) {
			$content = (string) $content;
		}

		if ( $this->target === '_blank' ) {
			$content .= $this->new_window_text();
		}

		return $content;
	}

	public function get_link_header_args() {
		return [
			Text_Controller::CONTENT => $this->link_header,
			Text_Controller::TAG     => 'h3',
		];
	}

	public function get_link_description_args() {
		return [
			Text_Controller::CONTENT => $this->description,
			Text_Controller::TAG     => 'div',
		];
	}

	private function new_window_text(): string {
		return sprintf( '<span class="u-visually-hidden">%s</span>', __( 'Opens new window', 'tribe' ) );
	}

}
