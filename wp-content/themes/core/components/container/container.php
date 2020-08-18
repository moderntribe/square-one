<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\container\Controller::factory( $args );
$content = $c->content();
if ( empty( $content ) ) {
	return;
}
?>

<<?php echo $c->tag(); ?>
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>
	<?php echo $content; ?>
</<?php echo $c->tag(); ?>>
