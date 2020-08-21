<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\no_results;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class No_Results_Controller extends Abstract_Controller {
	public const CLASSES = 'classes';
	public const TITLE   = 'attachment';
	public const CONTENT = 'attrs';

	private array  $classes;
	private string $title;
	private string $content;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes = (array) $args[ self::CLASSES ];
		$this->title   = (string) $args[ self::TITLE ];
		$this->content = (string) $args[ self::CONTENT ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::TITLE   => esc_html__( 'No Posts', 'tribe' ),
			self::CONTENT => esc_html__( 'Sorry, but there are currently no posts to see at this time.', 'tribe' ),
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'no-results' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_title(): string {
		return esc_html( $this->title );
	}

	public function get_content(): string {
		return esc_html( $this->content );
	}

}
