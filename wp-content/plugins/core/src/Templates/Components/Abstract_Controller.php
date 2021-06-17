<?php declare(strict_types=1);
/**
 * Base class for other controllers to extend.
 *
 * @package Tribe
 */

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;
use Tribe\Project\Post_Types\Page\Page;

/**
 * Class for other controllers to extend.
 */
abstract class Abstract_Controller {

	/**
	 * Merge the passed arguments with the default and
	 * required arguments defined by the controller
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected function parse_args( array $args ): array {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			if ( ! is_array( $value ) ) {
				throw new \UnexpectedValueException( esc_html__( 'Required arguments should be of the type array', 'tribe' ) );
			}
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		return $args;
	}

	/**
	 * Default arguments are merged with the passed
	 * arguments to fill in empty values
	 *
	 * @return array
	 */
	protected function defaults(): array {
		return [];
	}

	/**
	 * Required arguments should be arrays of values
	 * that will be merged with values passed in args.
	 *
	 * E.g., if a controller accepts optional classes,
	 * but should also _always_ have a 'special' class
	 * appended, this would return:
	 * [ 'classes' => [ 'special' ] ]
	 *
	 * @return array[]
	 */
	protected function required(): array {
		return [];
	}

	/**
	 * @param array $args
	 *
	 * @return static
	 */
	public static function factory( array $args = [] ) {
		return tribe_project()->container()->make( static::class, [ 'args' => $args ] );
	}

	public function render_breadcrumbs(): void {
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'page',
			[
				Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs(),
			]
		);
	}

	/**
	 * Returns breadcrumbs for the post.
	 *
	 * @return array \Tribe\Project\Templates\Models\Breadcrumb[] Breadcrumbs for the post.
	 */
	protected function get_breadcrumbs(): array {
		// Start of breadcrumbs.
		$breadcrumbs = [
			new Breadcrumb( get_site_url(), esc_html__( 'Home', 'tribe' ) ),
		];

		// Secondary Breadcrumbs.
		if ( is_singular() ) {
			$breadcrumbs = $this->get_singular_breadcrumbs( $breadcrumbs );
		} elseif ( is_archive() ) {
			$breadcrumbs = $this->get_taxonomy_archive_breadcrumbs( $breadcrumbs );
		}
	
		return $breadcrumbs;
	}

	/**
	 * Returns singular breadcrumbs.
	 *
	 * @param array $breadcrumbs Current breadcrumbs.
	 * @return array             Modified breadcrumbs.
	 */
	protected function get_singular_breadcrumbs( array $breadcrumbs = [] ): array {
		if ( is_post_type_hierarchical( get_post_type() ) ) {
			$parent_ids = get_post_ancestors( get_the_ID() );
			
			if ( ! empty( $parent_ids ) ) {
				foreach ( array_reverse( $parent_ids ) as $parent_id ) {
					$breadcrumbs[] = new Breadcrumb( get_the_permalink( $parent_id ), get_the_title( $parent_id ) );
				}
			}
		}

		return $breadcrumbs;
	}

	/**
	 * Returns taxonomy archive breadcrumbs.
	 *
	 * @param array $breadcrumbs Current breadcrumbs.
	 * @return array             Modified breadcrumbs.
	 */
	protected function get_taxonomy_archive_breadcrumbs( array $breadcrumbs = [] ): array {
		global $wp_query;

		// Bail early if no taxonomy.
		if ( empty( $wp_query->queried_object->term_id ) ) {
			return [];
		}

		// Hold link output.
		$output = '';

		// Get ancestors for term.
		$ancestors = get_ancestors( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy );

		// Loop through term ancestors to build breadcrumbs.
		if ( ! empty( $ancestors ) ) {
			foreach ( array_reverse( $ancestors ) as $ancestor ) {
				$term = get_term_by(
					'id',
					$ancestor,
					$wp_query->queried_object->taxonomy
				);

				// Skip if not a valid term.
				if ( ! ( $term instanceof \WP_Term ) ) {
					continue;
				}

				$breadcrumbs[] = new Breadcrumb( get_term_link( $term ), $term->name );
			}
		}

		$breadcrumbs[] = new Breadcrumb( '', single_term_title( '', false ) );

		return $breadcrumbs;
	}
}
