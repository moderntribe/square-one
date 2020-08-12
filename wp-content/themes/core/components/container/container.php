<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\container\Controller::factory( $args );
?>

<<?php echo $c->tag(); ?>
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>
	<?php echo $c->content(); ?>
</<?php echo $c->tag(); ?>>
