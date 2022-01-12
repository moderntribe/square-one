<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\pagination\comments;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Comments_Pagination_Controller extends Abstract_Controller {

	public const ATTRS   = 'attrs';
	public const CLASSES = 'classes';
	public const PAGED   = 'paged';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private bool $paged;
	private int $comment_page;
	private int $max_pages;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs        = (array) $args[ self::ATTRS ];
		$this->classes      = (array) $args[ self::CLASSES ];
		$this->comment_page = (int) ( get_query_var( 'cpage' ) ?: 1 );
		$this->max_pages    = (int) get_comment_pages_count();
		$this->paged        = (bool) $args[ self::PAGED ];
	}

	public function is_paged(): bool {
		return $this->paged && $this->max_pages > 1;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_previous_link(): string {
		if ( $this->comment_page <= 1 ) {
			return '';
		}

		$prev_page = $this->comment_page - 1;

		return (string) tribe_template_part( 'components/container/container', null, [
			'tag'     => 'li',
			'content' => defer_template_part( 'components/link/link', null, [
				'classes' => [],
				'content' => esc_html__( '&larr; Older Comments' ),
				'url'     => esc_url( get_comments_pagenum_link( $prev_page ) ),
			] ),
		] );
	}

	public function get_next_link(): string {
		$next_page = $this->comment_page + 1;

		if ( $next_page > $this->max_pages ) {
			return '';
		}

		return (string) tribe_template_part( 'components/container/container', null, [
			'tag'     => 'li',
			'content' => defer_template_part( 'components/link/link', null, [
				'classes' => [],
				'content' => esc_html__( 'Newer Comments &rarr;', 'tribe' ),
				'url'     => esc_url( get_comments_pagenum_link( $next_page, $this->max_pages ) ),
			] ),
		] );
	}

	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
			self::PAGED   => false,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'pagination', 'pagination--comments' ],
			self::ATTRS   => [
				'aria-label' => esc_attr__( 'Comments Pagination', 'tribe' ),
			],
		];
	}

}
