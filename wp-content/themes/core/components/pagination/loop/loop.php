<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */

$controller = \Tribe\Project\Templates\Components\pagination\loop\Controller::factory( $args );

$links = $controller->links();

if ( empty( $links ) ) {
	return;
}

?>
<nav <?php echo $controller->wrapper_classes(); ?> <?php echo $controller->wrapper_attrs(); ?>>

	<ul <?php echo $controller->list_classes(); ?> <?php echo $controller->list_attrs(); ?>>

		<?php foreach ( $links as $link ) { ?>
			<li <?php echo $controller->item_classes(); ?> <?php echo $controller->item_attrs(); ?>>
				<?php get_template_part( 'components/link/link', 'pagination', $link ); ?>
			</li>
		<?php } ?>

	</ul>

</nav>

