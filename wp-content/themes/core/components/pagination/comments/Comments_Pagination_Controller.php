<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\pagination\comments;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Comments_Pagination_Controller extends Abstract_Controller {
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const PAGED   = 'paged';

	private array $classes;
	private array $attrs;
	private bool  $paged;
	private int   $comment_page;
	private int   $max_pages;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}
		$this->classes      = (array) $args[ self::CLASSES ];
		$this->attrs        = (array) $args[ self::ATTRS ];
		$this->paged        = (bool) $args[ self::PAGED ];
		$this->comment_page = (int) ( get_query_var( 'cpage' ) ?: 1 );
		$this->max_pages    = (int) get_comment_pages_count();
	}

	public function defaults() {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
			self::PAGED   => false,
		];
	}

	public function required() {
		return [
			self::CLASSES => [ 'pagination', 'pagination--comments' ],
			self::ATTRS   => [
				'aria-labelledby' => 'pagination__label-comments',
			],
		];
	}

	/**
	 * @return bool
	 */
	public function is_paged() {
		return $this->paged && $this->max_pages > 1;
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_previous_link() {
		if ( $this->comment_page <= 1 ) {
			return '';
		}

		$prev_page = $this->comment_page - 1;

		return tribe_template_part( 'components/container/container', null, [
			'tag'     => 'li',
			'content' => defer_template_part( 'components/link/link', null, [
				'classes' => [],
				'content' => __( '&larr; Older Comments' ),
				'url'     => esc_url( get_comments_pagenum_link( $prev_page ) ),
			] ),
		] );
	}

	/**
	 * @return false|string
	 */
	public function get_next_link() {
		$next_page = $this->comment_page + 1;

		if ( $next_page > $this->max_pages ) {
			return '';
		}

		return tribe_template_part( 'components/container/container', null, [
			'tag'     => 'li',
			'content' => defer_template_part( 'components/link/link', null, [
				'classes' => [],
				'content' => __( 'Newer Comments &rarr;', 'tribe' ),
				'url'     => esc_url( get_comments_pagenum_link( $next_page, $this->max_pages ) ),
			] ),
		] );
	}
}
