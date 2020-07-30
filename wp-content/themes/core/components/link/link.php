<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */

$controller = \Tribe\Project\Templates\Components\link\Controller::factory( $args );

?>
<a <?php echo $controller->classes(); ?> <?php echo $controller->attrs(); ?>><?php echo $controller->content(); ?></a>

