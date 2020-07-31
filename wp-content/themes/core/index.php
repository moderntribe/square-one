<?php

// TODO: QUIBLE. We have mixed templates calls in templates and controller. While flexible, maybe we need a consistent way?
//  $controller = \Tribe\Project\Templates\Components\page\index\Controller::factory(); // here instead?
get_template_part( 'components/page/index/index' );
