<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\header\navigation\Header_Navigation_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Header_Navigation_Controller::factory( $args );

if ( ! $c->has_menu() ) {
	return;
}
?>
<nav <?php $c->get_classes(); ?> <?php $c->get_attrs(); ?>>
	<ol <?php $c->get_nav_list_classes(); ?>>
		<?php echo $c->get_menu(); ?>
	</ol>
</nav>

