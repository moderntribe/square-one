<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\pagination\comments\Comments_Pagination_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Comments_Pagination_Controller::factory( $args );

if ( ! $c->is_paged() ) {
	return;
}
?>

<nav <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<ol class="pagination__list">
		<?php $c->get_previous_link(); ?>
		<?php $c->get_next_link(); ?>
	</ol>
</nav>


