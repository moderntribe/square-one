<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\pagination\loop\Controller::factory( $args );

$links = $c->links();

if ( empty( $links ) ) {
	return;
}

?>
<nav <?php echo $c->classes(); ?> <?php echo $c->attributes(); ?>>

	<ul <?php echo $c->list_classes(); ?> <?php echo $c->list_attrs(); ?>>

		<?php foreach ( $links as $link ) { ?>
			<li <?php echo $c->item_classes(); ?> <?php echo $c->item_attrs(); ?>>
				<?php get_template_part( 'components/link/link', 'pagination', $link ); ?>
			</li>
		<?php } ?>

	</ul>

</nav>

