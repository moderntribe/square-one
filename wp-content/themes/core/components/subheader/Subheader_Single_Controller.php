<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\subheader;

use Tribe\Project\Templates\Components\link\Link_Controller;

class Subheader_Single_Controller extends Subheader_Controller {

	public const TAG_NAME             = 'tag_name';
	public const TAG_LINK             = 'tag_link';
	public const DATE                 = 'date';
	public const AUTHOR               = 'author';
	public const SHOULD_RENDER_BYLINE = 'should_render_byline';

	private ?string $tag_name          = null;
	private ?string $tag_link          = null;
	private string $date               = '';
	private string $author             = '';
	private bool $should_render_byline = true;

	public function __construct( array $args = [] ) {
		parent::__construct( $args );
		$args = $this->parse_args( $args );

		$this->tag_name             = (string) $args[ self::TAG_NAME ];
		$this->tag_link             = (string) $args[ self::TAG_LINK ];
		$this->date                 = (string) $args[ self::DATE ];
		$this->author               = (string) $args[ self::AUTHOR ];
		$this->should_render_byline = (bool) $args[ self::SHOULD_RENDER_BYLINE ];
	}

	public function get_tag_args(): array {
		return [
			Link_Controller::CONTENT => $this->get_tag_name(),
			Link_Controller::URL     => $this->get_tag_link(),
			Link_Controller::CLASSES => ['a-tag-link'],
		];
	}

	public function get_tag_name(): string {
		return $this->tag_name;
	}

	public function get_tag_link(): string {
		return $this->tag_link;
	}

	public function get_date(): string {
		return $this->date;
	}

	public function get_author(): string {
		return $this->author;
	}

	public function should_render_byline(): bool {
		return $this->should_render_byline;
	}

	protected function required(): array {
		$required = parent::required();

		$required[ self::CLASSES ]         = [ 'c-subheader', 'c-subheader--single' ];
		$required[ self::CONTENT_CLASSES ] = [ 'c-subheader__content', 'c-subheader__content--single' ];

		return $required;
	}

	protected function defaults(): array {
		$defaults = parent::defaults();

		$defaults[ self::CLASSES ]              = [];
		$defaults[ self::TAG_NAME ]             = null;
		$defaults[ self::TAG_LINK ]             = null;
		$defaults[ self::DATE ]                 = '';
		$defaults[ self::AUTHOR ]               = '';
		$defaults[ self::SHOULD_RENDER_BYLINE ] = true;

		return $defaults;
	}

}
