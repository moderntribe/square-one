<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\statistic\Controller::factory( $args );
?>

<<?php echo $c->tag(); ?>
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>

	<?php echo $c->render_value(); ?>

	<?php echo $c->render_label(); ?>

</<?php echo $c->tag(); ?>>
