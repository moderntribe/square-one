<?php


namespace Tribe\Project\Templates\Content\Pagination;

use Tribe\Project\Twig\Twig_Template;

class Comments extends Twig_Template {
	public function get_data(): array {
		return [
			'pagination' => $this->get_pagination(),
		];
	}

	protected function get_pagination() {
		$count = get_comment_pages_count();
		$paged = (bool) ( $count > 1 ? get_option( 'page_comments' ) : false );

		$data = [
			'paged'         => $paged,
			'max_num_pages' => $count,
			'previous'      => '',
			'next'          => '',
		];

		if ( $paged ) {
			$data['previous'] = get_previous_comments_link( __( '&larr; Older Comments', 'tribe' ) );
			$data['next']     = get_next_comments_link( __( 'Newer Comments &rarr;', 'tribe' ) );
		}

		return $data;
	}
}
