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

		<?php echo $c->get_toggle(); ?>

		<div <?php $c->get_dropdown_classes(); ?> <?php $c->get_dropdown_attrs(); ?>>
			<div <?php $c->get_tablist_classes(); ?> <?php $c->get_tablist_attrs(); ?>>
				<?php foreach ( $c->get_buttons() as $button ) { ?>
					<button
						<?php echo Markup_Utils::class_attribute( $button['classes'] ); ?>
						<?php echo Markup_Utils::concat_attrs( $button['attrs'] ); ?>
					><?php echo $button['content']; ?></button>
				<?php } ?>
			</div>
		</div>

	</div>

	<div class="c-tabs__tabpanels-wrapper">
		<?php foreach ( $c->get_tabs() as $tab ) { ?>
			<div
				<?php echo Markup_Utils::class_attribute( $tab['classes'] ); ?>
				<?php echo Markup_Utils::concat_attrs( $tab['attrs'] ); ?>
			><?php echo $tab['content']; ?></div>
		<?php } ?>
	</div>

</div>
