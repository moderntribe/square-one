<?php
/**
 * Functions: Theme Architecture
 *
 * Setup our WordPress data architecture (menus, sidebars, etc.)
 *
 * @since tribe-square-one 1.0
 */


/**
 * Register menus
 */

register_nav_menus(
    array(
        'primary' => 'Menu: Site',
        'footer'  => 'Menu: Footer'
    )
);


/**
 * Register sidebars
 */

register_sidebar(
    array(
        'name'          => 'Sidebar: Main',
        'id'            => 'sidebar-main',
        'description'   => 'Default sidebar',
        'class'         => '',
        'before_widget' => '<aside class="widget %2$s" role="widget">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>' 
    )
);