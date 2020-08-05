<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\sidebar\Controller::factory( $args );

if ( ! $controller->active() ) {
	return;
}
?>
<section class="sidebar" role="complementary">
	<?php dynamic_sidebar( $controller->id() ); ?>
</section>
