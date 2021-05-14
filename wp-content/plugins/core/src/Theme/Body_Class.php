<?php declare(strict_types=1);

namespace Tribe\Project\Theme;

use Tribe\Project\Theme\Appearance\Appearance_Identifier;

class Body_Class {

	private Appearance_Identifier $appearance_identifier;

	public function __construct( Appearance_Identifier $appearance_identifier ) {
		$this->appearance_identifier = $appearance_identifier;
	}

	/**
	 * @filter body_class
	 *
	 * @param string[] $classes
	 *
	 * @return array
	 */
	public function add( array $classes ): array {

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
		$classes[] = $this->appearance_identifier->get_body_class();

		return $classes;
	}

	private function is_singular(): bool {
		if ( ! is_singular() ) {
			return false;
		}

		global $post;

		if ( empty( $post ) || empty( $post->ID ) ) {
			return false;
		}

		return true;
	}

	private function have_panels(): bool {
		return ( function_exists( 'have_panels' ) && have_panels() );
	}

	private function post_name_class( $post ): string {
		return sanitize_html_class( $post->post_type . '-' . $post->post_name );
	}
}
