<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\content_block\Controller::factory( $args );
?>

<<?php echo $c->tag(); ?>
	<?php echo $c->classes(); ?>
	<?php echo$c->attributes(); ?>
>

	<?php echo $c->render_leadin(); ?>

	<?php echo $c->render_title(); ?>

	<?php echo $c->render_content(); ?>

	<?php echo $c->render_cta(); ?>

</<?php echo $c->tag(); ?>>
