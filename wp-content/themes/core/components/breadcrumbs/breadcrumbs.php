<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\breadcrumbs\Controller::factory( $args );

?>
<div <?php echo $controller->wrapper_classes(); ?> <?php echo $controller->wrapper_attrs(); ?>>
	<ul <?php echo $controller->main_classes(); ?> <?php echo $controller->main_attrs(); ?>>

		<?php foreach ( $controller->items() as $item ) { ?>
			<li <?php echo $controller->item_classes(); ?> <?php echo $controller->item_attrs(); ?>>
				<a href="<?php echo esc_url( $item->url ); ?>" <?php echo $controller->link_classes(); ?> <?php echo $controller->link_attrs(); ?>>
					<?php echo esc_html( $item->label ); ?>
				</a>
			</li>
		<?php } ?>
	</ul>
</div>
