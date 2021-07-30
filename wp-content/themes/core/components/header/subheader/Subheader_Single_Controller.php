<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Subheader_Single_Controller extends Subheader_Controller {

	public const DATE                 = 'date';
	public const AUTHOR               = 'author';
	public const SHOULD_RENDER_BYLINE = 'should_render_byline';

	private string $date               = '';
	private string $author             = '';
	private bool $should_render_byline = true;

	public function __construct( array $args = [] ) {
		parent::__construct( $args );
		$args = $this->parse_args( $args );

		$this->date                 = (string) $args[ self::DATE ];
		$this->author               = (string) $args[ self::AUTHOR ];
		$this->should_render_byline = (bool) $args[ self::SHOULD_RENDER_BYLINE ];
	}

	protected function required(): array {
		$required = parent::required();

		$required[ self::CLASSES ]         = [ 'c-subheader', 'blah' ];
		$required[ self::CONTENT_CLASSES ] = [ 'c-subheader__content', 'c-subheader__content--single' ];

		return $required;
	}

	protected function defaults(): array {
		$defaults = parent::defaults();

		$defaults[ self::DATE ]                 = '';
		$defaults[ self::AUTHOR ]               = '';
		$defaults[ self::SHOULD_RENDER_BYLINE ] = true;

		return $defaults;
	}

	public function get_taxonomy_terms(): array {
		global $post;

		$terms = get_the_terms( $post->ID, Category::NAME );
		if ( ! $terms ) {
			return [];
		}

		return $terms;
	}

	public function parse_term_to_link_args( \WP_Term $tax): array {
		return [
			Link_Controller::CONTENT => $tax->name,
			Link_Controller::URL     => get_term_link( $tax ),
			Link_Controller::CLASSES => ['a-tag-link'],
		];
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

}
