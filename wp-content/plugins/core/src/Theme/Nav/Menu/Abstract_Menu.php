<?php

namespace Tribe\Project\Theme\Nav\Menu;

use Tribe\Project\Theme\Nav\Menu;

abstract class Abstract_Menu {
    /**
     * @var array
     */
    protected $args = [];

    /**
     * @return string
     */
    public function get_menu() {
        return Menu::menu( $this->args );
    }
}