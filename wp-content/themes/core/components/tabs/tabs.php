<?php
declare( strict_types=1 );

use \Tribe\Libs\Utils\Markup_Utils;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\tabs\Controller::factory( $args );

if ( empty( $c->tabs ) ) {
	return;
}

?>

<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div class="c-tabs__tablist-wrapper">

		<?php if ( $c->layout === $c::LAYOUT_VERTICAL ) {
			echo $c->get_dropdown_toggle();
		} ?>

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
