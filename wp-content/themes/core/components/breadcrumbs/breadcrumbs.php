<?php
declare( strict_types=1 );
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Breadcrumbs_Controller::factory( $args );

?>
<div
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attrs(); ?>
>

	<ul <?php echo $c->get_main_classes(); ?> <?php echo $c->get_main_attrs(); ?>>

		<?php foreach ( $c->get_items() as $item ) { ?>
			<li <?php echo $c->get_item_classes(); ?> <?php echo $c->get_item_attrs(); ?>>
				<a href="<?php echo esc_url( $item->url ); ?>" <?php echo $c->get_link_classes(); ?> <?php echo $c->get_link_attrs(); ?>>
					<?php echo esc_html( $item->label ); ?>
				</a>
			</li>
		<?php } ?>

	</ul>

</div>
