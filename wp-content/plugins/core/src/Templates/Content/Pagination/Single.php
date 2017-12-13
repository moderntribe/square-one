<?php


namespace Tribe\Project\Templates\Content\Pagination;


use Tribe\Project\Twig\Twig_Template;

class Single extends Twig_Template {
	public function get_data(): array {
		return [
			'pagination' => $this->get_pagination(),
		];
	}

	protected function get_pagination() {
		$previous = get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		$pagination = [
			'has_pagination' => ( ! empty( $previous ) || ! empty( $next ) ),
			'has_previous'   => ! empty( $previous ),
			'has_next'       => ! empty( $next ),
			'next'           => [],
			'previous'       => [],
		];

		if ( ! empty( $previous ) ) {
			$pagination[ 'previous' ] = [
				'title'     => get_the_title( $previous ),
				'permalink' => get_permalink( $previous ),
			];
		}
		if ( ! empty( $next ) ) {
			$pagination[ 'next' ] = [
				'title'     => get_the_title( $next ),
				'permalink' => get_permalink( $next ),
			];
		}

		return $pagination;
	}
}