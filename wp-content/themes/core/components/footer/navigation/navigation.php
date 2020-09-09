<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\footer\navigation\Navigation_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Navigation_Controller::factory( $args );

if ( ! $c->has_menu() ) {
	return;
}
?>

<nav <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<ol <?php echo $c->get_nav_list_classes(); ?>>
		<?php echo $c->get_menu(); ?>
	</ol>

</nav>
