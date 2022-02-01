<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\link;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Link_Controller extends Abstract_Controller {

	public const ADD_ARIA_LABEL = 'add_aria_label';
	public const ARIA_LABEL     = 'aria_label';
	public const ATTRS          = 'attrs';
	public const CLASSES        = 'classes';
	public const CONTENT        = 'content';
	public const DESCRIPTION    = 'description';
	public const HEADER         = 'header';
	public const TARGET         = 'target';
	public const URL            = 'url';

	/**
	 * @var string|\Tribe\Project\Templates\Components\Deferred_Component
	 */
	private $content;

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private bool $add_aria_label;
	private string $aria_label;
	private string $description;
	private string $link_header;
	private string $target;
	private string $url;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->add_aria_label = (bool) $args[ self::ADD_ARIA_LABEL ];
		$this->aria_label     = (string) $args[ self::ARIA_LABEL ];
		$this->attrs          = (array) $args[ self::ATTRS ];
		$this->classes        = (array) $args[ self::CLASSES ];
		$this->content        = (string) $args[ self::CONTENT ];
		$this->description    = (string) $args[ self::DESCRIPTION ];
		$this->link_header    = (string) $args[ self::HEADER ];
		$this->target         = (string) $args[ self::TARGET ];
		$this->url            = (string) $args[ self::URL ];
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
		$content = (string) $this->content;

		if ( $this->target === '_blank' ) {
			$content .= $this->new_window_text();
		}

		return $content;
	}

	public function get_link_header_args(): array {
		return [
			Text_Controller::CONTENT => $this->link_header,
			Text_Controller::TAG     => 'h3',
		];
	}

	public function get_link_description_args(): array {
		return [
			Text_Controller::CONTENT => $this->description,
			Text_Controller::TAG     => 'div',
		];
	}

	protected function defaults(): array {
		return [
			self::ADD_ARIA_LABEL => false,
			self::ARIA_LABEL     => '',
			self::ATTRS          => [],
			self::CLASSES        => [],
			self::CONTENT        => '',
			self::DESCRIPTION    => '',
			self::HEADER         => '',
			self::TARGET         => '',
			self::URL            => '',
		];
	}

	protected function required(): array {
		return [];
	}

	private function new_window_text(): string {
		return sprintf( '<span class="u-visually-hidden">%s</span>', esc_html__( 'Opens new window', 'tribe' ) );
	}

}
