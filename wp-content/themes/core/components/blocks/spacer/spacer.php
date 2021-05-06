<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\spacer\Spacer_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Spacer_Block_Controller::factory( $args ); ?>

<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>></div>
