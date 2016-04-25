<?php
/**
 * Customize WordPress Nav Menu Walker
 *
 * @since core 1.0
 */


class Core_Walker_Nav_Menu extends Walker_Nav_Menu {

    function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }

        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    /**
      * Starts the list before the elements are added.
      *
      * @see Walker::start_lvl()
      *
      * @since 3.0.0
      *
      * @param string $output Passed by reference. Used to append additional content.
      * @param int    $depth  Depth of menu item. Used for padding.
      * @param array  $args   An array of arguments. @see wp_nav_menu()
      */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
    
        $indent = str_repeat("\t", $depth);
        
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    
    }

    /**
       * Ends the list of after the elements are added.
       *
       * @see Walker::end_lvl()
       *
       * @since 3.0.0
       *
       * @param string $output Passed by reference. Used to append additional content.
       * @param int    $depth  Depth of menu item. Used for padding.
       * @param array  $args   An array of arguments. @see wp_nav_menu()
       */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
    
        $indent = str_repeat("\t", $depth);

        $output .= "$indent</ul>\n";
    
    }

    /**
     * @see      Walker::start_el()
     * @since    3.0.0
     *
     * @param string       $output Passed by reference. Used to append additional content.
     * @param object       $item   Menu item data object.
     * @param int          $depth  Depth of menu item. Used for padding.
     * @param array|object $args
     * @param int          $id
     *
     * @internal param int $current_page Menu item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        // Clean up unneeded class names
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $current_indicators = array( 'current-menu-ancestor', 'current-menu-parent' );
        $newClasses = array();

        foreach( $classes as $el ){
            if ( in_array( $el, $current_indicators ) ) {
                array_push( $newClasses, $el );
            }
        }

        // Customize and add our parent and current classes
        $newClasses[] = ( $args->has_children ) ? 'is-parent' : '';
        $newClasses[] = ( $item->current ) ? 'is-current' : '';

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $newClasses ), $item ) );
        if($class_names!='') $class_names = ' class="'. esc_attr( $class_names ) . '"';

        // Parent anchor class
        $parent_anchor_class = ( $args->has_children ) ? ' class="is-parent-anchor"' : '';

        // Output menu items
        $output .= $indent . '<li' . $value . $class_names . '>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . $parent_anchor_class . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * @see   Walker::end_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args
     */
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}