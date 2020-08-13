<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\pagination\comments;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Theme\Pagination_Util;

class Controller extends Abstract_Controller {
	/**
	 * @var array
	 */
	protected $wrapper_classes;
	protected $wrapper_attrs;
	protected $paged;
	protected $comment_page;
	protected $max_pages;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );
		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}
		$this->wrapper_classes = (array) $args[ 'wrapper_classes' ];
		$this->wrapper_attrs   = (array) $args[ 'wrapper_attrs' ];
		$this->paged           = (bool) $args[ 'paged' ];
		$this->comment_page    = get_query_var( 'cpage' )
			? intval( get_query_var( 'cpage' ) )
			: 1;
		$this->max_pages       = get_comment_pages_count();
	}

	public function defaults() {
		return [
			'wrapper_classes' => [],
			'wrapper_attrs'   => [],
			'paged'           => false,
		];
	}

	public function required() {
		return [
			'wrapper_classes' => [ 'pagination', 'pagination--comments' ],
			'wrapper_attrs'   => [
				'aria-labelledby' => 'pagination__label-comments',
			],
		];
	}

	/**
	 * @return bool
	 */
	public function is_paged() {
		return $this->paged;
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function wrapper_attrs(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
	}

	public function get_previous_link() {
		if ( intval( $this->comment_page ) <= 1 ) {
			return '';
		}

		$prev_page = intval( $this->comment_page ) - 1;

		return tribe_template_part( 'components/link/link', null, [
			'classes' => [],
			'content' => __( '&laquo; Older Comments' ),
			'url'     => esc_url( get_comments_pagenum_link( $prev_page ) ),
		] );
	}

	/**
	 * @return false|string
	 */
	public function get_next_link() {
		if ( ! $this->comment_page ) {
			$this->comment_page = 1;
		}

		if ( ( $this->comment_page + 1 ) > $this->max_pages ) {
			return '';
		}

		$next_page = intval( $this->comment_page ) + 1;

		return tribe_template_part( 'components/link/link', null, [
			'classes' => [],
			'content' => __( 'Newer Comments &rarr;', 'tribe' ),
			'url'     => esc_url( get_comments_pagenum_link( $next_page, $this->max_pages ) ),
		] );
	}
}
