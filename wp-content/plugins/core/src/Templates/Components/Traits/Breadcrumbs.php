<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\Traits;

use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

trait Breadcrumbs {

	/**
	 * Holds breadcrumbs for the post.
	 *
	 * @var array
	 */
	protected $response = [];

	/**
	 * Returns breadcrumbs for the post.
	 *
	 * @return array \Tribe\Project\Templates\Models\Breadcrumb[] Breadcrumbs for the post.
	 */
	public function get_breadcrumbs(): array {
		// Start of breadcrumbs.
		$this->response[] = new Breadcrumb( get_site_url(), esc_html__( 'Home', 'tribe' ) );

		// Secondary Breadcrumbs.
		if ( is_singular() ) {
			$this->get_singular_breadcrumbs();
		} elseif ( is_archive() ) {
			$this->get_taxonomy_archive_breadcrumbs();
		}
	
		return [
			Breadcrumbs_Controller::BREADCRUMBS => $this->response,
		];
	}

	/**
	 * Returns singular breadcrumbs.
	 *
	 * @return void
	 */
	protected function get_singular_breadcrumbs(): void {
		if ( ! is_post_type_hierarchical( get_post_type() ) ) {
			return;
		}

		$parent_ids = get_post_ancestors( get_the_ID() );
		
		// Bail early if no parent IDs.
		if ( empty( $parent_ids ) ) {
			return;
		}

		foreach ( array_reverse( $parent_ids ) as $parent_id ) {
			$this->response[] = new Breadcrumb( get_the_permalink( $parent_id ), get_the_title( $parent_id ) );
		}
	}

	/**
	 * Returns taxonomy archive breadcrumbs.
	 *
	 * @return void
	 */
	protected function get_taxonomy_archive_breadcrumbs(): void {
		global $wp_query;

		// Bail early if no taxonomy.
		if ( empty( $wp_query->queried_object->term_id ) ) {
			return;
		}

		// Hold link output.
		$output = '';

		// Get ancestors for term.
		$ancestors = get_ancestors( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy );

		// Loop through term ancestors to build breadcrumbs.
		if ( ! empty( $ancestors ) ) {
			foreach ( array_reverse( $ancestors ) as $ancestor ) {
				$term = get_term_by( 'id', $ancestor, $wp_query->queried_object->taxonomy );

				// Skip if not a valid term.
				if ( ! ( $term instanceof \WP_Term ) ) {
					continue;
				}

				$this->response[] = new Breadcrumb( get_term_link( $term ), $term->name );
			}
		}

		$this->response[] = new Breadcrumb( '', single_term_title( '', false ) );
	}

}
