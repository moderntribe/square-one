<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\pagination\loop\Loop_Pagination_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Loop_Pagination_Controller::factory( $args );

if ( empty( $c->get_links() ) ) {
	return;
}

?>
<nav <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<ul <?php echo $c->get_list_classes(); ?> <?php echo $c->get_list_attrs(); ?>>
		<?php foreach ( $c->get_links() as $link ) { ?>
			<li <?php echo $c->get_item_classes(); ?> <?php echo $c->get_item_attrs(); ?>>
				<?php get_template_part( 'components/link/link', 'pagination', $link ); ?>
			</li>
		<?php } ?>
	</ul>
</nav>

