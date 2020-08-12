<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\sidebar\Controller::factory( $args );

if ( ! $c->active() ) {
	return;
}
?>
<section <?php echo $c->classes(); ?> <?php echo $c->attributes(); ?>>
	<?php dynamic_sidebar( $c->id() ); ?>
</section>
