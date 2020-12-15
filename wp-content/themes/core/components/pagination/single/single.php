<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\pagination\single\Single_Pagination_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Single_Pagination_Controller::factory( $args );

if ( empty( $c->get_previous_link_args() ) &&  empty( $c->get_next_link_args() ) ) {
	return;
}
?>
<nav <?php echo $c->get_classes(); ?><?php echo $c->get_attrs(); ?> >
	<ol <?php echo $c->get_container_classes(); ?><?php echo $c->get_container_attrs(); ?>>
		<?php if ( ! empty( $c->get_previous_link_args() ) ) { ?>
			<li <?php echo $c->get_list_classes(); ?> <?php echo $c->get_list_attrs(); ?>>
				<?php get_template_part( 'components/link/link', 'pagination', $c->get_previous_link_args() ); ?>
			</li>
		<?php } ?>

		<?php if ( ! empty( $c->get_next_link_args() ) ) { ?>
			<li <?php echo $c->get_list_classes(); ?> <?php echo $c->get_list_attrs(); ?>>
				<?php get_template_part( 'components/link/link', 'pagination', $c->get_next_link_args() ); ?>
			</li>
		<?php } ?>
	</ol>
</nav>
