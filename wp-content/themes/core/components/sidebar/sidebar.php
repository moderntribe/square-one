<?php
declare( strict_types=1 );
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Sidebar_Controller::factory( $args );

if ( ! $c->is_active() ) {
	return;
}
?>
<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php dynamic_sidebar( $c->get_sidebar_id() ); ?>
</section>
