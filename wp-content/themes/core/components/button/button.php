<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\button\Controller::factory( $args );

if ( ! $c->has_content() ) {
	return;
}
?>

<?php echo $c->wrapper_tag_open(); ?>

	<button
		<?php echo $c->classes(); ?>
		<?php echo $c->attributes(); ?>
	>
		<?php echo $c->content(); ?>
	</button>

<?php echo $c->wrapper_tag_close(); ?>
