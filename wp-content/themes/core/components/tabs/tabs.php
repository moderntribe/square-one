<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\tabs\Controller::factory( $args );

if ( empty( $c->get_tab_panels() ) ) {
	return;
}

?>

<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div class="c-tabs__tablist-wrapper">

		<?php echo $c->get_dropdown_toggle(); ?>

		<div <?php $c->get_dropdown_classes(); ?> <?php $c->get_dropdown_attrs(); ?>>
			<div <?php $c->get_tablist_classes(); ?> <?php $c->get_tablist_attrs(); ?>>
				<?php foreach ( $c->get_tab_buttons() as $button ) {
					echo $button;
				} ?>
			</div>
		</div>

	</div>

	<div class="c-tabs__tabpanels-wrapper">
		<?php foreach ( $c->get_tab_panels() as $tab ) {
			echo $tab;
		} ?>
	</div>

</div>
