<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\button\Button_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Button_Controller::factory( $args );

if ( ! $c->has_content() ) {
	return;
}
?>
<button <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php echo $c->content(); ?>
</button>
