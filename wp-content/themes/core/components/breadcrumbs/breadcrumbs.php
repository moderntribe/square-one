<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\breadcrumbs\Controller::factory( $args );

?>
<div
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>

	<ul <?php echo $c->main_classes(); ?> <?php echo $c->main_attrs(); ?>>

		<?php foreach ( $c->items() as $item ) { ?>
			<li <?php echo $c->item_classes(); ?> <?php echo $c->item_attrs(); ?>>
				<a href="<?php echo esc_url( $item->url ); ?>" <?php echo $c->link_classes(); ?> <?php echo $c->link_attrs(); ?>>
					<?php echo esc_html( $item->label ); ?>
				</a>
			</li>
		<?php } ?>

	</ul>

</div>
