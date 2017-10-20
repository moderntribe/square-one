<?php

namespace Tribe\Project\Theme\Nav\Menu;


use Tribe\Project\Theme\Nav\Walker_Nav_Menu_Primary;

class Primary_Menu extends Abstract_Menu {

    const NAME = 'primary';

    public function __construct( $args = [] ) {
        $defaults = [
            'theme_location'  => self::NAME,
            'container'       => false,
            'container_class' => '',
            'menu_class'      => '',
            'menu_id'         => '',
            'depth'           => 3,
            'items_wrap'      => '%3$s',
            'fallback_cb'     => false,
            'echo'            => false,
            'walker'          => new Walker_Nav_Menu_Primary(),
        ];

        $this->args = wp_parse_args( $args, $defaults );
    }
}