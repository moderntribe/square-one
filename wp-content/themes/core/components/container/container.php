<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\container\Controller::factory( $args );
$content    = $controller->content();
if ( empty( $content ) ) {
	return;
}
?>
<<?php echo $controller->tag(); ?>
	<?php echo $controller->classes(); ?>
	<?php echo $controller->attributes(); ?>
>
	<?php echo $controller->content(); ?>
</<?php echo $controller->tag(); ?>>
