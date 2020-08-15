<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\pagination\comments\Comments_Pagination_Controller::factory( $args );

if ( ! $c->is_paged() ) {
	return;
}
?>

<nav <?php echo $c->classes(); ?>
	<?php echo $c->attrs(); ?>>

	<h3 id="pagination__label-comments" class="a11y-visual-hide">
		<?php esc_html_e('Comments Pagination', 'tribe');?>
	</h3>

	<ol class="pagination__list">
		<?php $c->get_previous_link(); ?>
		<?php $c->get_next_link(); ?>
	</ol>

</nav>


