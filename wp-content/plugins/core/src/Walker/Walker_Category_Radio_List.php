<?php


namespace Tribe\Project\Walker;
use Walker_Category_Checklist;

class Walker_Category_Radio_List extends Walker_Category_Checklist {


	/**
	 * Start the element output.
	 *
	 * @see   Walker::start_el()
	 *
	 * @since 2.5.1
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category The current term object.
	 * @param int    $depth    Depth of the term in reference to parents. Default 0.
	 * @param array  $args     An array of arguments. @see wp_terms_checklist()
	 * @param int    $id       ID of the current term.
	 */
	public function start_el( &$output, $category, $depth = 0, $args = [ ], $id = 0 ) {
		if ( empty( $args[ 'taxonomy' ] ) ) {
			$taxonomy = 'category';
		} else {
			$taxonomy = $args[ 'taxonomy' ];
		}

		$hierarchical = is_taxonomy_hierarchical( $taxonomy );

		if ( $taxonomy == 'category' ) {
			$name = 'post_category';
		} else {
			$name = 'tax_input[' . $taxonomy . ']';
		}
		$args[ 'popular_cats' ] = empty( $args[ 'popular_cats' ] ) ? [ ] : $args[ 'popular_cats' ];
		$class = in_array( $category->term_id, $args[ 'popular_cats' ] ) ? ' class="popular-category"' : '';

		$args[ 'selected_cats' ] = empty( $args[ 'selected_cats' ] ) ? [ ] : $args[ 'selected_cats' ];

		/** This filter is documented in wp-includes/category-template.php */
		$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
			'<label class="selectit"><input value="' . ( $hierarchical ? $category->term_id : $category->slug ) . '" type="radio" name="' . $name . '[]" id="in-' . $taxonomy . '-' . $category->term_id . '"' .
			checked( in_array( $category->term_id, $args[ 'selected_cats' ] ), true, false ) .
			disabled( empty( $args[ 'disabled' ] ), false, false ) . ' /> ' .
			esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
	}
}