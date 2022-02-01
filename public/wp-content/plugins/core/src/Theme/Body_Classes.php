<?php declare(strict_types=1);

namespace Tribe\Project\Theme;

use WP_Post;

/**
 * @TODO rename this to a proper class name.
 */
class Body_Classes {

	/**
	 * @param array $classes
	 *
	 * @filter body_class
	 *
	 * @return array
	 */
	public function body_classes( array $classes ): array {

		if ( ! $this->is_singular() ) {
			return $classes;
		}

		global $post;

		// Panels
		if ( $this->have_panels() ) {
			$classes[] = 'has-panels';
			if ( empty( $post->post_content ) ) {
				$classes[] = 'is-panels-page';
			} else {
				$classes[] = 'has-page-content';
			}
		}

		$classes[] = $this->post_name_class( $post );

		return $classes;
	}

	private function is_singular(): bool {
		if ( ! is_singular() ) {
			return false;
		}

		global $post;

		return ! empty( $post ) && ! empty( $post->ID );
	}

	private function have_panels(): bool {
		return function_exists( 'have_panels' ) && have_panels();
	}

	private function post_name_class( WP_Post $post ): string {
		return sanitize_html_class( $post->post_type . '-' . $post->post_name );
	}

}
