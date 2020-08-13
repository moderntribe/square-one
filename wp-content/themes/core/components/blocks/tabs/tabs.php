<?php
/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\tabs\Controller::factory( $args );
?>
<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>
		<?php echo $c->get_header(); ?>
		<?php echo $c->get_tabs(); ?>
	</div>
</section>
