<?php declare(strict_types=1);

namespace Tribe\Project\Theme;

class Body_Classes {

	/**
	 * @param $classes
	 *
	 * @return array
	 *
	 * @filter body_class
	 */
	public function body_classes( $classes ) {

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
		return  function_exists( 'have_panels' ) && have_panels();
	}

	private function post_name_class( $post ): string {
		return sanitize_html_class( $post->post_type . '-' . $post->post_name );
	}

}
