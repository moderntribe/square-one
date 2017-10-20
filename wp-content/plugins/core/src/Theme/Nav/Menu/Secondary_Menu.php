<?php

namespace Tribe\Project\Theme\Nav\Menu;

class Secondary_Menu extends Abstract_Menu {

    const NAME = 'secondary';

    public function __construct( $args = [] ) {
        $defaults = [
            'theme_location'  => self::NAME,
            'container'       => false,
            'container_class' => '',
            'menu_class'      => '',
            'menu_id'         => '',
            'depth'           => 1,
            'items_wrap'      => '%3$s',
            'fallback_cb'     => false,
            'echo'            => false,
            'item_spacing'    => 'discard',
        ];

        $this->args = wp_parse_args( $args, $defaults );
    }
}