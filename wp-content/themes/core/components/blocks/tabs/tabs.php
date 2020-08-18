<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Tabs_Block_Controller::factory( $args );
?>
<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>
		<?php echo $c->get_header(); ?>
		<?php echo $c->get_tabs(); ?>
	</div>
</section>
